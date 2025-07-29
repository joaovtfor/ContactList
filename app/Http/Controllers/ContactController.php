<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Contact::teste('John Doe', '1234567892', 'State', 'City', 'test2@test.com', 'Category');
        // Contact::destroy(1);
        $contacts = Contact::getContacts();
        return Inertia::render('Contact/Index', [
            'contacts' => $contacts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Contact/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if (isset($data['state'])) {
            $data['state'] = $data['state']['sigla'];
        }

        $data['phone'] = preg_replace('/\D+/', '', $data['phone']);

        $request->replace($data);

        $messages = [
            'name.required'     => 'É necessário preencher o nome',
            'phone.required'    => 'É necessário preencher o telefone',
            'email.required'    => 'É necessário preencher o email',
            'category.required' => 'É necessário selecionar uma categoria',
            'state.required'    => 'É necessário selecionar um Estado',
            'city.required'     => 'É necessário selecionar uma cidade',
            'email.email'       => 'É necessário um E-Mail válido',
            'phone.digits'      => 'O telefone deve ter exatamente 11 dígitos',
        ];

        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|digits:11',
            'email'         => 'required|email|max:255',
            'category'      => 'required|string|max:11',
            'state'         => 'required|string',
            'city'          => 'required|string|max:50',
        ], $messages);
        Contact::create($request->all());

        return redirect()->route('contacts.index')->with('message', 'Contato criado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);

        return Inertia::render('Contact/Show', [
            'contact' => $contact
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contact = Contact::findOrFail($id);
        return Inertia::render('Contact/Edit', [
            'contact' => $contact
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cleanPhone = preg_replace('/\D+/', '', $request->input('phone', ''));
        $request->merge(['phone' => $cleanPhone]);

        $messages = [
            'name.required'     => 'É necessário preencher o nome',
            'phone.required'    => 'É necessário preencher o telefone',
            'email.required'    => 'É necessário preencher o email',
            'category.required' => 'É necessário selecionar uma categoria',
            'state.required'    => 'É necessário selecionar um Estado',
            'city.required'     => 'É necessário selecionar uma cidade',
            'email.email'       => 'É necessário um E-Mail válido',
            'phone.digits'      => 'O telefone deve ter exatamente 11 dígitos',
        ];

        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'required|digits:11',
            'email'         => 'required|email|max:255',
            'category'      => 'required|string|max:11',
            'state'         => 'required|array',
            'city'          => 'required|string|max:50',
        ], $messages);

        $sigla = $validated['state']['sigla'];

        $contact = Contact::findOrFail($id);
        $contact->name     = $validated['name'];
        $contact->phone    = $cleanPhone;
        $contact->email    = $validated['email'];
        $contact->category = $validated['category'];
        $contact->state    = $sigla;
        $contact->city     = $validated['city'];
        $contact->save();

        return redirect()
            ->route('contacts.index')
            ->with('message', 'Contato atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contato excluído com sucesso.');
    }

    public function search(Request $request)
    {
        $term = $request->input('term', '');

        $contacts = Contact::search($term);

        return response()->json([
            'term'     => $term,
            'contacts' => $contacts,
        ], 200);
    }
}
