<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class ATP_Embed_Calendar_Elementor_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'atp_embed_calendar_elementor';
    }

    public function get_title() {
        return __('ATP Calendar Embed', 'elementor-calendar-embed');
    }

    public function get_icon() {
        return 'eicon-calendar';
    }

    public function get_categories() {
        return ['general'];
    }

protected function _register_controls() {
    // Calendar Type Selection
    $this->start_controls_section(
        'content_section',
        [
            'label' => __('Calendar Settings', 'elementor-calendar-embed'),
            'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
        ]
    );

$this->add_control(
    'calendar_type',
    [
        'label' => __('Calendar Type', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::SELECT,
        'options' => [
            'google' => __('Google Calendar', 'elementor-calendar-embed'),
            'google_appointments' => __('Google Appointment Scheduling', 'elementor-calendar-embed'),
            'microsoft' => __('Microsoft Calendar', 'elementor-calendar-embed'),
            'calendly' => __('Calendly', 'elementor-calendar-embed'),
        ],
        'default' => 'google',
    ]
);

    // Repeater for Google Calendar IDs and Colors
    $this->add_control(
        'google_calendars',
        [
            'label' => __('Google Calendars', 'elementor-calendar-embed'),
            'type' => \Elementor\Controls_Manager::REPEATER,
            'fields' => [
                [
                    'name' => 'calendar_id',
                    'label' => __('Calendar ID', 'elementor-calendar-embed'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => __('Enter your Google Calendar ID', 'elementor-calendar-embed'),
                    'default' => '',
                ],
                [
                    'name' => 'calendar_color',
                    'label' => __('Calendar Color', 'elementor-calendar-embed'),
                    'type' => \Elementor\Controls_Manager::COLOR,
                    'default' => '#D50000', // Default color
                ],
            ],
            'default' => [],
            'title_field' => '{{{ calendar_id }}}',
            'condition' => [
                'calendar_type' => ['google'],
            ],
            'description' => __('Enter multiple Google Calendar IDs and assign a color to each one.', 'elementor-calendar-embed'),
        ]
    );

$this->add_control(
    'google_appointments_url',
    [
        'label' => __('Google Appointment Scheduling URL', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::TEXTAREA, // Use TEXTAREA instead of TEXT
        'placeholder' => __('Enter your Google Appointment Scheduling URL', 'elementor-calendar-embed'),
        'condition' => [
            'calendar_type' => ['google_appointments'],
        ],
        'description' => __(
            'To get the embeddable URL:<br>' .
            '1. Go to your Google Appointment Scheduling page.<br>' .
            '2. Click the <strong>Embed</strong> button.<br>' .
            '3. Copy the URL from the iframe code (e.g., <code>https://calendar.google.com/calendar/appointments/schedules/AcZssZ08TxC30VaF4i7ppu1r1SM8rAD4ilrvsCw0dSKkL-RReeNmIlAV4LgbwMl-eUNToBAew4rfjOXD?gv=true</code>).',
            'elementor-calendar-embed'
        ),
    ]
);


    // Single URL/ID for Microsoft Calendar and Calendly
    $this->add_control(
        'calendar_url',
        [
            'label' => __('Calendar URL or ID', 'elementor-calendar-embed'),
            'type' => \Elementor\Controls_Manager::TEXTAREA,
            'placeholder' => __('Enter your calendar URL or ID', 'elementor-calendar-embed'),
            'description' => __('For Microsoft Calendar, use the embed URL. For Calendly, use your Calendly link.', 'elementor-calendar-embed'),
            'condition' => [
                'calendar_type' => ['microsoft', 'calendly'],
            ],
        ]
    );

    // View Type Selection
    $this->add_control(
        'calendar_view',
        [
            'label' => __('View Type', 'elementor-calendar-embed'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'month' => __('Month', 'elementor-calendar-embed'),
                'week' => __('Week', 'elementor-calendar-embed'),
                'day' => __('Day', 'elementor-calendar-embed'),
                'agenda' => __('Agenda', 'elementor-calendar-embed'),
            ],
            'default' => 'month',
            'condition' => [
                'calendar_type' => ['google', 'microsoft'],
            ],
            'description' => __('Select the view type for Google or Microsoft Calendar.', 'elementor-calendar-embed'),
        ]
    );

    // Timezone Selection (only for Google Calendar)
    $this->add_control(
        'calendar_timezone',
        [
            'label' => __('Timezone', 'elementor-calendar-embed'),
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => $this->get_timezone_options(),
            'default' => 'UTC',
            'condition' => [
                'calendar_type' => ['google'],
            ],
            'description' => __('Select the timezone for the Google Calendar.', 'elementor-calendar-embed'),
        ]
    );
       $this->end_controls_section();

        // Styling Options
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Styling', 'elementor-calendar-embed'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

$this->add_control(
    'calendar_height',
    [
        'label' => __('Height', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::SLIDER,
        'size_units' => ['px', '%', 'rem', 'em', 'vh', 'vw'], // Add vh and vw
        'range' => [
            'px' => [
                'min' => 100,
                'max' => 1000,
            ],
            '%' => [
                'min' => 10,
                'max' => 100,
            ],
            'rem' => [
                'min' => 1,
                'max' => 100, // Increase the max value
            ],
            'em' => [
                'min' => 1,
                'max' => 100, // Increase the max value
            ],
            'vh' => [
                'min' => 10,
                'max' => 100,
            ],
            'vw' => [
                'min' => 10,
                'max' => 100,
            ],
        ],
        'default' => [
            'unit' => 'px',
            'size' => 600,
        ],
        'selectors' => [
            '{{WRAPPER}} .calendar-embed iframe' => 'height: {{SIZE}}{{UNIT}};',
        ],
    ]
);

// Border Radius
$this->add_control(
    'iframe_border_radius',
    [
        'label' => __('Border Radius', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}} .calendar-embed iframe' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

// Box Shadow
$this->add_group_control(
    \Elementor\Group_Control_Box_Shadow::get_type(),
    [
        'name' => 'iframe_box_shadow',
        'label' => __('Box Shadow', 'elementor-calendar-embed'),
        'selector' => '{{WRAPPER}} .calendar-embed iframe',
    ]
);

// Background Color
$this->add_control(
    'widget_background_color',
    [
        'label' => __('Background Color', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::COLOR,
        'selectors' => [
            '{{WRAPPER}} .calendar-embed' => 'background-color: {{VALUE}};',
        ],
    ]
);

// Padding
$this->add_control(
    'widget_padding',
    [
        'label' => __('Padding', 'elementor-calendar-embed'),
        'type' => \Elementor\Controls_Manager::DIMENSIONS,
        'size_units' => ['px', '%'],
        'selectors' => [
            '{{WRAPPER}} .calendar-embed' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
    ]
);

        $this->end_controls_section();
    }

    protected function get_timezone_options() {
        $timezones = [
            'UTC' => 'UTC',
            'America/New_York' => 'New York (EDT)',
            'America/Chicago' => 'Chicago (CDT)',
            'America/Denver' => 'Denver (MDT)',
            'America/Los_Angeles' => 'Los Angeles (PDT)',
            'Europe/London' => 'London (BST)',
            'Europe/Paris' => 'Paris (CEST)',
            'Asia/Tokyo' => 'Tokyo (JST)',
            'Australia/Sydney' => 'Sydney (AEST)',
            // Add more timezones as needed
        ];

        return $timezones;
    }

protected function convert_google_appointments_url($url) {
    // If the URL is already an embed URL, return it as-is
    if (strpos($url, 'https://calendar.google.com/calendar/appointments/schedules/') !== false) {
        return $url;
    }

    // Extract the schedule ID from the shortened URL
    $parsed_url = parse_url($url);
    if (isset($parsed_url['path'])) {
        $path = trim($parsed_url['path'], '/');
        $parts = explode('/', $path);
        $schedule_id = end($parts);

        if (!empty($schedule_id)) {
            return "https://calendar.google.com/calendar/appointments/schedules/{$schedule_id}?gv=true";
        }
    }

    return '';
}

protected function render() {
    $settings = $this->get_settings_for_display();
    $calendar_type = $settings['calendar_type'];

    if ($calendar_type === 'google') {
        $google_calendars = $settings['google_calendars'];
        if (empty($google_calendars)) {
            echo __('Please enter at least one Google Calendar ID.', 'elementor-calendar-embed');
            return;
        }

        $src_params = [];
        foreach ($google_calendars as $calendar) {
            $calendar_id = trim($calendar['calendar_id']);
            $calendar_color = trim($calendar['calendar_color']);
            if (!empty($calendar_id)) {
                $src_params[] = 'src=' . urlencode($calendar_id) . '&color=' . urlencode($calendar_color);
            }
        }

        if (empty($src_params)) {
            echo __('Please enter valid Google Calendar IDs.', 'elementor-calendar-embed');
            return;
        }

        // Construct the Google Calendar embed URL
        $view_param = '&mode=' . $settings['calendar_view'];
        $embed_url = "https://calendar.google.com/calendar/embed?" . implode('&', $src_params) . "&ctz={$settings['calendar_timezone']}{$view_param}";
        echo '<div class="calendar-embed"><iframe src="' . esc_url($embed_url) . '" style="border: 0" width="100%" frameborder="0" scrolling="no"></iframe></div>';
    } elseif ($calendar_type === 'google_appointments') {
        $google_appointments_url = trim($settings['google_appointments_url']);
        if (empty($google_appointments_url)) {
            echo __('Please enter a valid Google Appointment Scheduling URL.', 'elementor-calendar-embed');
            return;
        }

        // Convert the URL to the embeddable format
        $embed_url = $this->convert_google_appointments_url($google_appointments_url);
        if (empty($embed_url)) {
            echo __('Invalid Google Appointment Scheduling URL.', 'elementor-calendar-embed');
            return;
        }

        // Construct the Google Appointment Scheduling embed URL
        echo '<div class="calendar-embed"><iframe src="' . esc_url($embed_url) . '" style="border: 0" width="100%" frameborder="0" scrolling="no"></iframe></div>';
    } elseif ($calendar_type === 'microsoft') {
        $calendar_url = trim($settings['calendar_url']);
        if (empty($calendar_url)) {
            echo __('Please enter a Microsoft Calendar embed URL.', 'elementor-calendar-embed');
            return;
        }

        // Ensure the URL is a valid Outlook URL
        if (strpos($calendar_url, 'outlook.office.com') === false && strpos($calendar_url, 'outlook.office365.com') === false && strpos($calendar_url, 'outlook.live.com') === false) {
            echo __('Please enter a valid Microsoft Calendar embed URL.', 'elementor-calendar-embed');
            return;
        }

        $view_param = '&view=' . $settings['calendar_view'];
        echo '<div class="calendar-embed"><iframe src="' . esc_url($calendar_url) . $view_param . '" style="border: 0" width="100%" frameborder="0" scrolling="no"></iframe></div>';
    } elseif ($calendar_type === 'calendly') {
        $calendar_url = trim($settings['calendar_url']);
        if (empty($calendar_url)) {
            echo __('Please enter a Calendly link.', 'elementor-calendar-embed');
            return;
        }

        // Strip out "https://calendly.com/" if it exists
        $calendar_url = str_replace('https://calendly.com/', '', $calendar_url);
        $calendar_url = str_replace('http://calendly.com/', '', $calendar_url);

        echo '<div class="calendar-embed"><iframe src="https://calendly.com/' . esc_url($calendar_url) . '" style="border: 0" width="100%" frameborder="0" scrolling="no"></iframe></div>';
    }
}

}
