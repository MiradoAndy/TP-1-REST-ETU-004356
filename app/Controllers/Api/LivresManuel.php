<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\LivreModel;

/**
 * Étape A : un endpoint écrit « à la main », sans ResourceController.
 * Route : GET api/manuel/livres/(:num)
 */
class LivresManuel extends BaseController
{
    public function show($id)
    {
        $livre = model(LivreModel::class)->find($id);

        // TODO 1 : si le livre n'existe pas, renvoyer un 404
        //          avec un corps JSON {"erreur": "Livre ... introuvable"}.
        //          Indice : $this->response->setStatusCode(...)->setJSON(...)
        if (!$livre) {
            return $this->response->setStatusCode(404)->setJSON([
                'erreur' => "Livre {$id} introuvable"
            ]);
        } else {
            return $this->response->setStatusCode(200)->setJSON($livre);
        }

        // TODO 2 : sinon, renvoyer le livre en JSON avec le code 200.
    }
}
