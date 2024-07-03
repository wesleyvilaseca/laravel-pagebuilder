<?php

namespace App\Http\Middleware;

use App\Models\Event;
use App\Models\Theme;
use Closure;
use Illuminate\Http\Request;

class ActiveThemeEventMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $params = $request->route()->parameters();
        $eventUrl = $params['url'];
        $event = Event::where('url', $eventUrl)->first();
        $activeThemeEvent = Theme::find($event->theme_id);
        if ($activeThemeEvent->alias == env('ACTIVE_THEME')) {
            return $next($request);
        }
        
        $this->updateEnv('ACTIVE_THEME', $activeThemeEvent->alias);
        return $next($request);

    }

    /**
     * Atualiza o valor no arquivo .env
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    private function updateEnv($key, $value)
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
