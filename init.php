<?php
\Larakit\Boot::register_migrations(__DIR__ . '/migrations');
\Larakit\Boot::register_boot(__DIR__ . '/boot');
\Larakit\Boot::register_config(__DIR__ . '/config', true);