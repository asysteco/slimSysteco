<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use Exception;
use Slim\Routing\RouteContext;
use App\Domain\Sites\SiteFactory;
use Dflydev\FigCookies\SetCookie;
use Psr\Http\Message\ResponseInterface;
use App\Infrastructure\PDO\PdoDataAccess;
use Dflydev\FigCookies\FigRequestCookies;
use Dflydev\FigCookies\FigResponseCookies;
use App\Application\Actions\Error\Error404Action;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ValidateSiteMiddleware implements Middleware
{
    private const SESSION_VALUE = 'session-site';
    private const COOKIE_VALUE = 'site';

    private Error404Action $error404Action;
    private PdoDataAccess $pdo;

    public function __construct(Error404Action $error404Action, PdoDataAccess $pdo)
    {
        $this->error404Action = $error404Action;
        $this->pdo = $pdo;
    }

    public function process(Request $request, RequestHandler $handler): ResponseInterface
    {
        try {
            $activeSites = $this->getSites();
        } catch (Exception $e) {
            return $this->error404Action->execute();
        }
        session_start();

        $siteCookie = FigRequestCookies::get($request, self::COOKIE_VALUE);
        if (
            $siteCookie &&
            in_array($siteCookie->getValue(), $activeSites, true) &&
            (isset($_SESSION[self::SESSION_VALUE]) && $_SESSION[self::SESSION_VALUE] === $siteCookie->getValue())
        ) {
            return $handler->handle($request);
        }

        $this->clearSessionSite();

        $route = RouteContext::fromRequest($request)->getRoute();
        $site = $route->getArgument(self::COOKIE_VALUE);

        if (in_array($site, $activeSites, true)) {
            $_SESSION[self::SESSION_VALUE] = $site;

            return FigResponseCookies::set(
                $handler->handle($request),
                SetCookie::create(self::COOKIE_VALUE)->withValue($site)->withDomain(APP_HOST)
            );
        }

        return $this->error404Action->execute();
    }

    private function getSites(): array
    {
        $sql = "SELECT ID, Nombre FROM Centros WHERE Activo = :active";
        $params = [
            ':active' => 1
        ];

        $result = $this->pdo->query($sql, $params);
        
        $sites = [];
        if (!empty($result)) {
            $response = SiteFactory::createFromResultSet($result);

            foreach($response as $site) {
                $sites[] = $site->name();
            }
        }

        return $sites;
    }

    private function clearSessionSite(): void
    {
        if (isset($_SESSION[self::SESSION_VALUE])) {
            unset($_SESSION[self::SESSION_VALUE]);
        }
    }
}
 