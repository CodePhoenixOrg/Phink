<?php
/*
 * Copyright (C) 2016 David Blanchard
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

class PhinkLibrary {

    public static function mount() {

        $filenames = [ 
            'log/log.php',
            'core/static_object.php',
            'autoloader.php',
            'core/object.php',
            'css/css_builder.php',
            'js/js_builder.php',
            'core/custom_application.php',
            'ui/phar_interface.php',
            'ui/console_application.php',
            'web/http_transport_interface.php',
            'web/http_transport.php',
            'web/web_object_interface.php',
            'web/web_object.php',
            'web/web_application.php',
            'web/static_application.php',
            'rest/rest_application.php',
            'core/router.php',
            'web/curl.php',
            'web/response.php',
            'web/ui/custom_control.php',
            'web/ui/control.php',
            'mvc/custom_controller.php',
            'mvc/controller.php',
            'data/crud_queries.php',
            'mvc/model.php',
            'web/ui/code_generator.php',
            'mvc/custom_view.php',
            'mvc/view.php',
            'web/web_router.php',
            'rest/rest_router.php',
            'rest/rest_controller.php',
            'web/ui/script_maker.php',
            'web/ui/html_control.php',
            'core/registry.php',
            'web/request.php',
            'utils/file_utils.php',
            'crypto/crypto.php',
            'auth/authentication.php',
            'data/data_access.php',
            'data/server_type.php',
            'configuration/configurable.php',
            'configuration/configuration.php',
            'configuration/data/data_configuration.php',
            'configuration/data/file_configuration.php',
            'configuration/data/json_configuration.php',
            'configuration/data/url_configuration.php',
            'data/connection_interface.php',
            'data/sql_connection_interface.php',
            'data/data_statement_interface.php',
            'data/client/rest/rest_configuration.php',
            'data/client/rest/rest_connection.php',
            'data/client/rest/rest_data_statement.php',
            'data/client/pdo/types_mapper/pdo_data_types_mapper_interface.php',
            'data/client/pdo/types_mapper/pdo_custom_data_types_mapper.php',
            'data/client/pdo/types_mapper/pdo_mysql_data_types_mapper.php',
            'data/client/pdo/types_mapper/pdo_sqlite_data_types_mapper.php',
            'data/client/pdo/pdo_configuration.php',
            'data/client/pdo/pdo_connection.php',
            'data/client/pdo/pdo_data_statement.php',
            'utils/zip.php',
            'utils/string_utils.php',
            'collections/array_list.php',
            'text/regex_match.php',
            'text/regex.php',
            'xml/xml_match.php',
            'xml/xml_document.php',
            'xml/xml_utils.php',
            'mvc/partial_controller.php',
            'mvc/partial_view.php',
            'web/ui/html_element.php',
            'web/ui/html_pattern.php',
            'data/ui/data_tag.php',
            'data/ui/data_binder.php',
            'data/ui/data_analyser.php',
            'web/ui/widget/pager/pager.class.php',
            'web/ui/plugin/custom_plugin.php',
            'web/ui/plugin/table.php',
            'web/ui/plugin/ulli.php',
            'web/ui/plugin/olli.php',
            'web/ui/plugin/list.php',
            'web/ui/plugin/accordion.php',
            'web/ui/plugin_renderer.php',
            'web/ui/widget/plugin/plugin.class.php',
            'web/ui/widget/plugin/plugin_child.php'
        ];

        if(Phar::running() != '') {
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
