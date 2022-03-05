<?php

namespace App\View\Components;

use Exception;
use Illuminate\View\Component;

class ToastNotification extends Component
{
    public static array $availableStatuses = [
        'warning',
        'error',
        'success',
        'info',
        'question'
    ];

    /**
     * @param string $icon should be in ['warning', 'error', 'success', 'info', 'question']
     * @param string $title
     * @param string $text
     * @param int $timer
     * @throws Exception
     */
    public function __construct(
        public string $icon = 'success',
        public string $title = 'Действие выполнено успешно!',
        public string $text = '',
        public int    $timer = 3000,
    )
    {
        if (!in_array($this->icon, self::$availableStatuses)) {
            throw new Exception('Unavailable icon provided. Should be on of: ' .
                implode(', ', self::$availableStatuses));
        }
    }

    public function render()
    {
        return view('components.toast-notification');
    }
}
