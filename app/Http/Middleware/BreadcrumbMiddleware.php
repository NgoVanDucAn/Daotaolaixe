<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\Breadcrumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class BreadcrumbMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $breadcrumb = new \App\Services\Breadcrumb();
        $route = $request->route();
        $routeName = $route?->getName();
        $controllerAction = class_basename($route?->getAction('controller') ?? '');

        $breadcrumbsMap = config('breadcrumbs');
        $config = null;

        if ($routeName && isset($breadcrumbsMap[$routeName])) {
            $config = $breadcrumbsMap[$routeName];
        } elseif ($controllerAction && isset($breadcrumbsMap[$controllerAction])) {
            $config = $breadcrumbsMap[$controllerAction];
        }

        $pageTitle = '';

        if ($config) {
            foreach ($config['trail'] ?? [] as $item) {
                $breadcrumb->add($item['label'], $item['url']);
            }

            $pageTitle = $config['title'] ?? '';
        }

        $request->attributes->add([
            'breadcrumb' => $breadcrumb,
            'page_title' => $pageTitle,
        ]);

        view()->share([
            'breadcrumb' => $breadcrumb,
            'page_title' => $pageTitle,
        ]);

        return $next($request);
    }
}