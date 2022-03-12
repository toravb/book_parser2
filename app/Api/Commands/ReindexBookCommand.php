<?php

namespace App\Api\Commands;

use App\Models\AudioSeries;
use App\Models\Book;
use App\Api\Interfaces\Types;
use App\Models\Series;
use Elasticsearch\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReindexBookCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex {type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public array $searchableTypes;
    public string $searchableTypesString;
    public function __construct(private Client $elasticsearch, Types $types)
    {
        $this->searchableTypes = $types->getSearchableTypes();
        $this->searchableTypesString = implode(', ', array_keys($this->searchableTypes));
        $this->description = 'Reindex all records in Elasticsearch. Type can be ' .  $this->searchableTypesString;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $type = $this->argument('type');

        if(in_array($type, array_keys($this->searchableTypes))) {

            $this->info('Indexing all ' . $type);
            $model = new $this->searchableTypes[$type];
            try {
                $this->elasticsearch->indices()->delete([
                    'index' => $model->getSearchIndex()
                ]);
            } catch (\Exception $exception) {
                Log::error($exception);
            }

            $this->elasticsearch->indices()->create($this->settings($model));
            if($model instanceof Series) {
                $secondModel = new AudioSeries();

                foreach ($secondModel::cursor() as $currentModel) {
                    $this->elasticsearch->index([
                        'index' => $currentModel->getSearchIndex(),
                        'type' => $currentModel->getSearchType(),
                        'id' => $currentModel->getElasticKey(),
                        'body' => $currentModel->toSearchArray()
                    ]);
                    $this->output->write('.');
                }
            }

            foreach ($model::cursor() as $currentModel) {
                $this->elasticsearch->index([
                    'index' => $currentModel->getSearchIndex(),
                    'type' => $currentModel->getSearchType(),
                    'id' => $currentModel->getElasticKey(),
                    'body' => $currentModel->toSearchArray()
                ]);
                $this->output->write('.');
            }
            $this->info('\nDone!');
        } else {
            $this->error('Wrong type! Type must be one of ' . $this->searchableTypesString);
        }
    }

    private function settings($book)
    {
        return [
            'index' => $book->getSearchIndex(),
            'body' => [
                'settings' => [
                    'analysis' => [
                        'filter' => [
                            'russian_stop' => [
                                'type' => 'stop',
                                'stopwords' => '_russian_',
                            ],
                            'shingle' => [
                                'type' => 'shingle'
                            ],
                            'mynGram' => [
                                'type' => 'edge_ngram',
                                'min_gram' => 3,
                                'max_gram' => 10
                            ],
                            'length_filter' => [
                                'type' => 'length',
                                'min' => '3'
                            ],
                            'russian_stemmer' => [
                                'type' => 'stemmer',
                                'language' => 'russian'
                            ],
                            'english_stemmer' => [
                                'type' => 'stemmer',
                                'language' => 'english'
                            ]
                        ],
                        'analyzer' => [
                            'title' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',
                                'filter' => [
                                    'lowercase',
                                    'mynGram',
                                    'length_filter',
                                    'trim',
                                    'russian_stemmer',
                                    'english_stemmer',
                                    'russian_stop'
                                ]
                            ]
                        ]
                    ]
                ],
                'mappings' => [
                    'properties' => [
                        'title' => [
                            'type' => 'text',
                            'analyzer' => 'title'
                        ]
                    ]
                ]
            ]
        ];
    }
}
