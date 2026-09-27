<?php

namespace App\Controllers\Api;

use App\Models\LivreModel;
use CodeIgniter\RESTful\ResourceController;

/**
 * Étape B : la ressource « livres » avec ResourceController.
 * Route : $routes->resource('api/livres', ['except' => 'new,edit']);
 * Lancez « php spark routes » pour voir quelle méthode répond à quel verbe.
 *
 * Méthodes utiles du ResponseTrait :
 *   respond($data, $code)       respondCreated($data)     respondNoContent()
 *   respondDeleted($data)       failNotFound($message)    failValidationErrors($erreurs)
 *   fail($message, $code)
 * Le Model est disponible dans $this->model ; ses erreurs dans $this->model->errors().
 */
class Livres extends ResourceController
{
    protected $modelName = LivreModel::class;
    protected $format    = 'json';

    // GET /api/livres
    public function index()
    {
        // TODO : renvoyer les livres (200) au format du contrat :
        //        {"donnees": [ ...livres... ]}
        // Bonus : pagination avec ?page=2&par_page=10, et les clés page, par_page, total.
        $livres = model(LivreModel::class)->findAll();

        return $this->respond(['donnees' => $livres], 200);
    }

    // GET /api/livres/{id}
    public function show($id = null)
    {
        // TODO : 200 avec le livre, ou 404 s'il n'existe pas.
        $livre = model(LivreModel::class)->find($id);

        if (!$livre) {
            return $this->response->setStatusCode(404)->setJSON([
                'erreur' => "Livre {$id} non trouvé"
            ]);
        } else {
            return $this->respond($livre,200);
        }
    }

    // POST /api/livres
    public function create()
    {
        // TODO 1 : lire le corps JSON. Attention : getPost() ne marche pas ici.
        //          Indice : $this->request->getJSON(true)
        // TODO 2 : insérer ; si la validation échoue, répondre 400 avec les erreurs.
        // TODO 3 : répondre 201 avec la ressource créée
        //          ET un en-tête Location vers /api/livres/{id}.
        //          respondCreated() ajoute-t-il cet en-tête ? Vérifiez avec curl -i.
        $json = $this->request->getJSON(true);

        if ($this->model->insert($json)) {
            $this->response->setHeader('Location', site_url('api/livres/' . $this->model->getInsertID()));
            return $this->respondCreated($json);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // PUT et PATCH /api/livres/{id} arrivent ici tous les deux.
    public function update($id = null)
    {
        // TODO 1 : 404 si le livre n'existe pas.
        // TODO 2 : décider comment traiter PUT (remplacement complet)
        //          et PATCH (modification partielle).
        //          Indice : $this->request->getMethod()
        // TODO 3 : 200 avec la ressource à jour, ou 400 si les données sont invalides.

        // TODO 1 : 404 si le livre n'existe pas.
        $livre = $this->model->find($id);

        if (!$livre) {
            return $this->response->setStatusCode(404)->setJSON([
                'erreur' => "Le livre {$id} n'existe pas"
            ]);
        }

        // TODO 2 : PUT = remplacement complet, PATCH = modification partielle.
        $json = $this->request->getJSON(true);

        if ($this->request->getMethod() === 'put') {
            $donnees = $json;
        } else {
            $donnees = array_merge($livre, $json);
        }

        // TODO 3 : 200 avec la ressource à jour, ou 400 si invalide.
        if ($this->model->update($id, $donnees)) {
            $livreMisAJour = $this->model->find($id);
            return $this->respond($livreMisAJour, 200);
        } else {
            return $this->failValidationErrors($this->model->errors());
        }
    }

    // DELETE /api/livres/{id}
    public function delete($id = null)
    {
        // TODO : 404 si le livre n'existe pas, sinon supprimer.
        //        200 ou 204 ? Choisissez et justifiez en commentaire.
        // 204 puisque je ne renvoye pas de corps après la suppression
        $livre = $this->model->find($id);

        if ($livre) {
            $this->model->delete($id);
            return $this->response->setStatusCode(204);
        } else {
            return $this->response->setStatusCode(404)->setJSON([
                'erreur' => "Le livre {$id} n'existe pas"
            ]);
        }
    }
}
