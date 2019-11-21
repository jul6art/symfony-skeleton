<?php

/**
 * Created by VsWeb.
 * Project: symfony-skeleton
 * User: Jul6art
 * Date: 21/11/2019
 * Time: 21:39.
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class RefererService.
 */
class RefererService
{
    /**
     * @param Request $request
     * @param string  $domain
     *
     * @return string|null
     */
    public function getFormReferer(Request $request, string $domain): ?string
    {
        $referer = $request->headers->get('referer');
        if ($request->getUri() !== $referer and false !== strpos($referer, $request->getHost())) {
            $request->getSession()->set("referer_{$domain}", $referer);
        }

        return $request->getSession()->get("referer_{$domain}");
    }
}
