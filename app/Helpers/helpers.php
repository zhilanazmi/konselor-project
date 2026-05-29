<?php

use Carbon\Carbon;

if (! function_exists('format_date_indonesia')) {
    /**
     * Format tanggal ke format Indonesia
     */
    function format_date_indonesia(?string $date, string $format = 'd F Y'): string
    {
        if (! $date) {
            return '-';
        }

        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $formatted = Carbon::parse($date)->translatedFormat($format);

        return str_replace(
            array_keys(array_merge($months, $days)),
            array_values(array_merge($months, $days)),
            $formatted
        );
    }
}

if (! function_exists('format_datetime_indonesia')) {
    /**
     * Format tanggal dan waktu ke format Indonesia
     */
    function format_datetime_indonesia(?string $datetime): string
    {
        return format_date_indonesia($datetime, 'd F Y, H:i');
    }
}

if (! function_exists('get_status_badge_class')) {
    /**
     * Get CSS class for status badge
     */
    function get_status_badge_class(string $status): string
    {
        return match ($status) {
            'scheduled' => 'bg-sky-100 text-sky-600 dark:bg-sky-900/30 dark:text-sky-400',
            'ongoing' => 'bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400',
            'completed' => 'bg-success-100 text-success-600 dark:bg-success-600/25 dark:text-success-400',
            'cancelled' => 'bg-danger-100 text-danger-600 dark:bg-danger-600/25 dark:text-danger-400',
            default => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-900/30 dark:text-neutral-400',
        };
    }
}

if (! function_exists('get_status_label')) {
    /**
     * Get label for status
     */
    function get_status_label(string $status): string
    {
        return match ($status) {
            'scheduled' => 'Dijadwalkan',
            'ongoing' => 'Berlangsung',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($status),
        };
    }
}

if (! function_exists('get_service_type_badge_class')) {
    /**
     * Get CSS class for service type badge
     */
    function get_service_type_badge_class(string $serviceType): string
    {
        return match ($serviceType) {
            'individual' => 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400',
            'group' => 'bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400',
            'classroom' => 'bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400',
            'large_class' => 'bg-orange-100 text-orange-600 dark:bg-orange-900/30 dark:text-orange-400',
            default => 'bg-neutral-100 text-neutral-600 dark:bg-neutral-900/30 dark:text-neutral-400',
        };
    }
}

if (! function_exists('get_service_type_label')) {
    /**
     * Get label for service type
     */
    function get_service_type_label(string $serviceType): string
    {
        return match ($serviceType) {
            'individual' => 'Bimbingan Individu',
            'group' => 'Bimbingan Kelompok',
            'classroom' => 'Bimbingan Klasikal',
            'large_class' => 'Bimbingan Kelas Besar',
            default => ucfirst($serviceType),
        };
    }
}
