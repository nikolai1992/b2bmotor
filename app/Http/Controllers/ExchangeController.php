<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ExchangeCommerceML\Loader\Loader;
use App\Services\FillDb;

class ExchangeController extends Controller
{
    const SESSION_KEY = 'cml_import';

    private $dir = '/storage/exchange/';

    public function index(Request $request)
    {
        if ($request->get('type') == 'catalog') {
            switch ($request->mode) {
                case 'checkauth':
                    $login = config('exchange.login');
                    $password = config('exchange.password');

                    \Log::debug('exchange_1c: checkauth');

                    if ($_SERVER['PHP_AUTH_USER'] == $login && $_SERVER['PHP_AUTH_PW'] == $password) {
                        $request->session()->save();

                        $response = "success\n";
                        $response .= config('session.cookie')."\n";
                        $response .= \Session::getId()."\n";
                        $response .= "timestamp=".time();

                        \Session::put(self::SESSION_KEY.'_auth', $login);
                    } else {
                        $response = "failure\n";
                    }

                    return $response;
                case 'init':
                    \Log::debug('exchange_1c: init');

                    $response = "zip=no\n";
                    $response .= "file_limit=10000000\n";
                    $response .= "sessid=".\Session::getId()."\n";
                    $response .= "version=3.1";

                    return $response;

                case 'file':
                    $filename = basename($request->get('filename'));
                    $filePath = base_path().$this->dir.$filename;

                    \Log::debug('exchange_1c: file');
                    \Log::debug('exchange_1c: file filename: '.$filePath);

                    file_put_contents($filePath, file_get_contents('php://input'));

                    $response = "success\n";

                    return $response;
                case 'import':
                    $filename = basename($request->get('filename'));
                    $filePath = base_path().$this->dir.$filename;

                    \Log::debug('exchange_1c: import');
                    \Log::debug('exchange_1c: import filename: '.$filePath);

                    $load = new Loader($filePath);
                    $data = $load->getArray();
                    $type = $load->getType();

                    new FillDb($data, $type);

                    \Log::debug('exchange_1c: fill data');

                    unlink($filePath);

                    $response = "success\n";

                    return $response;
            }
        }
    }
}

