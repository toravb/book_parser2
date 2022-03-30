<?php

namespace App\Models;

use App\Api\Http\Requests\ReadingSettingsRequest;
use Illuminate\Database\Eloquent\Model;

class ReadingSettings extends Model
{
    /**
     * @var string[]
     */
    public static array $fonts = [
        'Times New Roman',
        'Georgia',
        'Arial',
        'Ubuntu',
        'Verdana',
    ];

    protected $fillable = [
        'user_id',
        'is_two_columns',
        'font_size',
        'screen_brightness',
        'font_name',
        'field_size',
        'row_height',
        'is_center_alignment'
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function saveReadingSettings(int $userId, ReadingSettingsRequest $request)
    {

        $this->updateOrCreate(
            [
                'user_id' => $userId
            ],
            [
                'is_two_columns' => $request->is_two_columns,
                'font_size' => $request->font_size,
                'screen_brightness' => $request->screen_brightness,
                'font_name' => $request->font_name,
                'field_size' => $request->field_size,
                'row_height' => $request->row_height,
                'is_center_alignment' => $request->is_center_alignment,
            ]
        );
    }
}
