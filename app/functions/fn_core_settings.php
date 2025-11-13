<?php
/**
 * Core Settings Functions
 *
 * Manage dynamic system settings stored in database
 */

/**
 * Get setting value by name
 *
 * @param string $name Setting name
 * @return mixed Setting value or null
 */
function fn_core_settings_get($name) {
    $query = "SELECT setting_value, setting_type FROM core_settings WHERE setting_name = :name LIMIT 1";
    $setting = fn_core_database_row($query, ['name' => $name]);

    if (!$setting) {
        return null;
    }

    // Cast value based on type
    switch ($setting['setting_type']) {
        case 'number':
            return (int)$setting['setting_value'];
        case 'boolean':
            return (bool)$setting['setting_value'];
        case 'json':
            return json_decode($setting['setting_value'], true);
        default:
            return $setting['setting_value'];
    }
}

/**
 * Echo setting value (for use in templates)
 *
 * @param string $name Setting name
 * @param string $default Default value if not found
 */
function fn_core_settings_echo($name, $default = '') {
    $value = fn_core_settings_get($name);
    echo $value !== null ? $value : $default;
}

/**
 * Set or update setting value
 *
 * @param string $name Setting name
 * @param mixed $value Setting value
 * @param string $type Setting type (text, number, boolean, json)
 * @param string $group Setting group
 * @return bool Success status
 */
function fn_core_settings_set($name, $value, $type = 'text', $group = 'general') {
    // Convert value based on type
    if ($type === 'json') {
        $value = json_encode($value);
    } elseif ($type === 'boolean') {
        $value = $value ? '1' : '0';
    }

    // Check if setting exists
    $existing = fn_core_settings_get($name);

    if ($existing !== null) {
        // Update existing
        $query = "UPDATE core_settings
                  SET setting_value = :value,
                      setting_type = :type,
                      setting_group = :group,
                      last_updated = NOW()
                  WHERE setting_name = :name";
    } else {
        // Insert new
        $query = "INSERT INTO core_settings (
                    setting_name,
                    setting_value,
                    setting_type,
                    setting_group,
                    created_date
                  ) VALUES (
                    :name,
                    :value,
                    :type,
                    :group,
                    NOW()
                  )";
    }

    return fn_core_execute_query($query, [
        'name' => $name,
        'value' => $value,
        'type' => $type,
        'group' => $group
    ]);
}

/**
 * Get all settings by group
 *
 * @param string $group Setting group
 * @return array Settings array
 */
function fn_core_settings_get_group($group) {
    $query = "SELECT * FROM core_settings WHERE setting_group = :group";
    return fn_core_database_rows($query, ['group' => $group]);
}

/**
 * Delete setting
 *
 * @param string $name Setting name
 * @return bool Success status
 */
function fn_core_settings_delete($name) {
    $query = "DELETE FROM core_settings WHERE setting_name = :name";
    return fn_core_execute_query($query, ['name' => $name]);
}
