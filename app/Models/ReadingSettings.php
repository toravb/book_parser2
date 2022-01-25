<?php

namespace App\Models;

use App\Http\Requests\ReadingSettingsRequest;
use Illuminate\Database\Eloquent\Model;

class ReadingSettings extends Model
{
    public static $fonts = array('Times New Roman', 'Georgia', 'Arial', 'Ubuntu', 'Verdana',);

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

    public function showCurrentReadingSettings(int $userId)
    {
        return $this->where('user_id', $userId)->get();
    }

    public function saveReadingSettings(int $userId, ReadingSettingsRequest $request)
    {

        $this->updateOrCreate(
            ['user_id' => $userId],
            [
                'is_two_columns' => $request->isTwoColumns,
                'font_size' => $request->fontSize,
                'screen_brightness' => $request->screenBrightness,
                'font_name' => $request->fontName,
                'field_size' => $request->fieldSize,
                'row_height' => $request->rowHeight,
                'is_center_alignment' => $request->isCenterAlignment,
            ]);
    }
}
