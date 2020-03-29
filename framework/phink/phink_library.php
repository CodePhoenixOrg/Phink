<?php
/*
 * Copyright (C) 2019 David Blanchard
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY, without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class PhinkLibrary
{

    public static function mount()
    {

        $filenames = [
            'core/constants.php',
            'log/log.php',
            'core/static_object.php',
            'core/object.php',
            'autoloader.php',
            'registry/class_info.php',
            'core/ini_loader.php',
            'core/custom_application.php',
            'registry/registry.php',
            'auth/authentication.php',
            'cache/cache.php',
            'collections/array_list.php',
            'configuration/configelement.php',
            'configuration/configurable.php',
            'configuration/configuration.php',
            'configuration/data/data_configuration.php',
            'configuration/data/file_configuration.php',
            'configuration/data/json_configuration.php',
            'configuration/data/url_configuration.php',
            'crypto/crypto.php',
            'css/css_builder.php',
            'data/connection_interface.php',
            'data/connector.php',
            'data/crud_queries.php',
            'data/data_access.php',
            'data/data_statement_interface.php',
            'data/server_type.php',
            'data/sql_connection_interface.php',
            'data/sql_parameters.php',
            'data/ui/data_analyser.php',
            'data/ui/data_binder.php',
            'data/ui/data_tag.php',
            'data/ui/select.php',
            'data/client/pdo/pdo_configuration.php',
            'data/client/pdo/pdo_connection.php',
            'data/client/pdo/pdo_data_statement.php',
            'data/client/pdo/schema_info/pdo_schema_info_interface.php',
            'data/client/pdo/schema_info/custom_pdo_schema_info.php',
            'data/client/pdo/schema_info/pdo_mysql_schema_info.php',
            'data/client/pdo/schema_info/pdo_sqlite_schema_info.php',
            'data/client/redis/redis_configuration.php',
            'data/client/redis/redis_connection.php',
            'data/client/rest/rest_configuration.php',
            'data/client/rest/rest_connection.php',
            'data/client/rest/rest_data_statement.php',
            // 'data/client/sqlserver/sqlserver_command.php',
            // 'data/client/sqlserver/sqlserver_configuration.php',
            // 'data/client/sqlserver/sqlserver_connection.php',
            // 'data/client/sqlserver/sqlserver_data_reader.php',
            'js/js_builder.php',
            'js/phink_builder.php',
            'text/regex.php',
            'text/regex_match.php',
            'ui/console_application.php',
            'ui/console_colors.php',
            'ui/phar_interface.php',
            'utils/array_utils.php',
            'utils/date_utils.php',
            'utils/file_utils.php',
            'utils/javascript_utils.php',
            'utils/mail_utils.php',
            'utils/sql_utils.php',
            'utils/string_utils.php',
            'utils/text_utils.php',
            'utils/zip.php',
            'web/curl.php',
            'web/http_transport_interface.php',
            'web/http_transport.php',
            'web/web_object_interface.php',
            'web/web_object.php',
            'core/router.php',
            'web/web_router.php',
            'web/request.php',
            'web/response.php',
            'web/web_application.php',
            'web/static_application.php',
            'xml/xml_document.php',
            'xml/xml_match.php',
            'xml/xml_utils.php',            
            'web/ui/code_generator.php',
            'mvc/action_info.php',
            'mvc/model.php',
            // 'mvc/collection.php',
            'web/ui/custom_control.php',
            'web/ui/custom_cached_control.php',
            'web/ui/control.php',
            'web/ui/partial_control.php',
            'mvc/custom_controller.php',
            'mvc/controller.php',
            'mvc/custom_view.php',
            'mvc/view.php',
            'mvc/partial_view.php',
            'mvc/partial_controller.php',
            'web/ui/html_control.php',
            'web/ui/html_element.php',
            // 'web/ui/html_objects.php',
            'web/ui/html_pattern.php',
            'web/ui/html_template.php',
            'web/ui/mvc_script_maker.php',
            'web/ui/plugin/custom_plugin.php',
            'web/ui/script_maker.php',
            'web/ui/widget/widget.php',
            'web/ui/widget/plugin/plugin_renderer.php',
            'web/ui/widget/plugin/plugin.class.php',
            'web/ui/widget/plugin/plugin_child.php',
            'web/ui/widget/user_component/user_component.class.php',
            'rest/rest_application.php',
            'rest/rest_controller.php',
            'rest/rest_router.php',
            'core/bootstrap.php',
        ];

        if (Phar::running() != '') {
            foreach ($filenames as $filename) {
                include pathinfo($filename, PATHINFO_BASENAME);
            }
        } else {
            foreach ($filenames as $filename) {
                include __DIR__ . "/" . $filename;
            }
        }
    }
}

PhinkLibrary::mount();
