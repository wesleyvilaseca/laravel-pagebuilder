<?php

namespace App\Supports\Helper;
class Utils
{
    public static function back_route_pagebuilder() : string
    {
        $event = @$_GET['event'] ?? null;
        $template = @$_GET['template'] ?? null;
        $route = '';

        if($event) {
            $route =  env('APP_URL') . '/gerenciar-evento/' . $event;
        }

        if($template) {
            $route =  env('APP_URL') . '/template-pages/' . $template;
        }

        return $route;
    }

    public static function getasset($route)
    {
        return asset($route);
    }

    public static function get_site_url()
    {
        return url('/');
    }

    public static function getPageRoute() {
        $template = @$_GET['template'] ?? null;
        $event = @$_GET['event'] ?? null;
        $page = @$_GET['page']  ?? null;
        $route = '';

        if($event) {
            $route =  env('APP_URL') . '/' . $event . '/' . $page;
        }

        if ($template) {
            $route =  env('APP_URL') . '/' . $template . '/' . $page;
        }
        
        return $route;
    }

    public static function getActiveTheme() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $event = @$_GET['event'];
        $template = @$_GET['template'];
        $defaultTheme = 'demo';

        if(@$_SESSION[$event]) {
            return $_SESSION[$event];
        }

        if(@$_SESSION[$template]) {
            return $_SESSION[$template];
        }

        return $defaultTheme;
    }

    public static function getRouteAdminSettingsPage() {
        $defaultRoute = '/settings/pages/build';
        $event = @$_GET['event'] ?? null;
        $template = @$_GET['template'] ?? null;
        $route = '';

        if ($event) {
            $route = $defaultRoute;
        }

        if ($template) {
            $route = '/settings/templates/build';
        }

        if (!$route) {
            return $defaultRoute;
        }

        return $route;
    }

        /**
     * Atualiza o valor no arquivo .env
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            // Ler o conteúdo do arquivo .env
            $env = file_get_contents($path);

            // Procurar a linha com a chave fornecida
            $oldValue = env($key);

            // Construir a linha com a nova chave e valor
            $newLine = "$key=$value";

            // Substituir ou adicionar a linha correspondente
            if (strpos($env, "$key=") !== false) {
                // Substituir a linha existente
                $env = preg_replace("/^$key=.*$/m", $newLine, $env);
            } else {
                // Adicionar nova linha ao final do arquivo
                $env .= "\n$newLine";
            }

            // Escrever o novo conteúdo no arquivo .env
            file_put_contents($path, $env);

            // Atualizar o valor no runtime da aplicação
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;

            // Opcional: reinicializar a configuração do Laravel
            app()->config->set($key, $value);

            return true;
        }

        return false;
    }
}