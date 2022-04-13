<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use function PHPUnit\Framework\isNull;

class User_settings extends Model
{
    use HasFactory;
    protected $table = 'user_settings';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'theme'
    ];

    protected static function detect_theme($user_id) {
        $settings = User_settings::where('user_id', $user_id)->first();

        if (!$settings) {
            return false;
        }

        return $settings->theme;
    }

    protected static function change_theme($user_id) {
        $theme = User_settings::detect_theme($user_id);
        $new_theme = 'dark';

        if (!$theme) {
            $settings_profile = new User_settings();
            $settings_profile->user_id = (int) $user_id;
            $settings_profile->theme = $new_theme;
            $settings_profile->save();
            return $new_theme;
        }
        $settings_profile = User_settings::where('user_id', $user_id)->first();

        if ($settings_profile->theme === 'dark') {
            $new_theme = 'light';
        }

        $settings_profile->update(['theme' => $new_theme]);
        return $new_theme;
    }
}
