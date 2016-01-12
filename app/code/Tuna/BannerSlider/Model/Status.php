<?php
namespace Tuna\BannerSlider\Model;

class Status
{
    const STATUS_ENABLE = 1;
    const STATUS_DISABLE = 0;

    public static function getAvailableStatuses()
    {
        return [
          self::STATUS_ENABLE => __('Enabled'),
          self::STATUS_DISABLE => __('Disabled')
        ];

    }
}