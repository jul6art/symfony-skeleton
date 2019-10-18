<?php

/**
 * Created by PhpStorm.
 * User: gkratz
 * Date: 10/04/2019
 * Time: 12:24.
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
