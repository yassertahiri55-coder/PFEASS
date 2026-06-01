<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Document;
use App\Models\Sinistre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    /**
     * Met à jour les anciens documents sans user_id (migration corrective)
     */
    public function assignUserIdToOldDocuments($userId = 1)
    {
        DB::table('documents')->whereNull('user_id')->update(['user_id' => $userId]);
        return response()->json(['message' => 'user_id mis à jour pour les anciens documents.']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }
        $documents = Document::where('user_id', $user->id)->get();
        return response()->json($documents->values());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return response()->json(['message' => 'Formulaire de création de document']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'sinistre_id' => 'required|exists:sinistres,id',
            'fichier' => 'required|file',
            'dossier_id' => 'nullable|exists:dossiers,id',
        ]);

        // Crée le dossier s'il n'existe pas
        $directory = storage_path('app/public/documents');
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        // Stocke le fichier uploadé
        $file = $request->file('fichier');
        $filename = uniqid('doc_') . '_' . $file->getClientOriginalName();
        $file->move($directory, $filename);
        Log::info('Fichier uploadé : ' . $filename);

        $data = [
            'nom' => $validated['nom'],
            'chemin' => $filename,
            'type' => $validated['type'],
            'sinistre_id' => $validated['sinistre_id'],
            'user_id' => Auth::id(),
        ];
        if (!empty($validated['dossier_id'])) {
            $data['dossier_id'] = $validated['dossier_id'];
        }

        $document = Document::create($data);

        // Correction : on force l'appel Eloquent via le modèle (évite conflit helper)
        $dossierQuery = [
            ['sinistre_id', '=', $validated['sinistre_id']]
        ];
        $dossier = \App\Models\Dossier::query()->where($dossierQuery)->first();
        if ($dossier && $dossier->statut !== 'en_attente') {
            $dossier->statut = 'en_attente';
            $dossier->save();
        }

        return response()->json($document, 201);
    }


    /**
     * Download the specified document file.
     */
    public function download(string $id)
    {
        $document = Document::findOrFail($id);
        $filePath = storage_path('app/public/documents/' . $document->chemin);
        if (!file_exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }
        $headers = [
            'Content-Type' => $document->type ?? 'application/octet-stream',
        ];
        return response()->download($filePath, $document->nom, $headers);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return response()->json(['message' => 'Formulaire d\'édition de document']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $document = Document::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'chemin' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
        ]);
        $document->update($validated);
        return response()->json($document);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $document = Document::findOrFail($id);
        $document->delete();
        return response()->json(['message' => 'Document supprimé']);
    }
}
