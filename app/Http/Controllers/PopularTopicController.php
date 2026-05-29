<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePopularTopicRequest;
use App\Http\Requests\UpdatePopularTopicRequest;
use App\Models\PopularTopic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PopularTopicController extends Controller
{
    public function index(Request $request): View
    {
        $topics = PopularTopic::query()
            ->with('creator')
            ->when($request->search, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->orderBy('order')
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('popular-topics.index', [
            'topics' => $topics,
            'pageTitle' => 'Kelola Topik Populer',
            'activePage' => 'Topik Populer',
        ]);
    }

    public function create(): View
    {
        return view('popular-topics.create', [
            'pageTitle' => 'Tambah Topik Populer',
            'activePage' => 'Topik Populer',
        ]);
    }

    public function store(StorePopularTopicRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = auth()->id();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs('popular-topics', $filename, 'public');
            $validated['image'] = $path;
        }

        PopularTopic::query()->create($validated);

        return redirect()
            ->route('guru-bk.popular-topics.index')
            ->with('success', 'Topik populer berhasil ditambahkan.');
    }

    public function edit(PopularTopic $popularTopic): View
    {
        return view('popular-topics.edit', [
            'topic' => $popularTopic,
            'pageTitle' => 'Edit Topik Populer',
            'activePage' => 'Topik Populer',
        ]);
    }

    public function update(UpdatePopularTopicRequest $request, PopularTopic $popularTopic): RedirectResponse
    {
        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($popularTopic->image && Storage::disk('public')->exists($popularTopic->image)) {
                Storage::disk('public')->delete($popularTopic->image);
            }

            $image = $request->file('image');
            $filename = time().'_'.$image->getClientOriginalName();
            $path = $image->storeAs('popular-topics', $filename, 'public');
            $validated['image'] = $path;
        }

        $popularTopic->update($validated);

        return redirect()
            ->route('guru-bk.popular-topics.index')
            ->with('success', 'Topik populer berhasil diperbarui.');
    }

    public function destroy(PopularTopic $popularTopic): RedirectResponse
    {
        // Delete image
        if ($popularTopic->image && Storage::disk('public')->exists($popularTopic->image)) {
            Storage::disk('public')->delete($popularTopic->image);
        }

        $popularTopic->delete();

        return redirect()
            ->route('guru-bk.popular-topics.index')
            ->with('success', 'Topik populer berhasil dihapus.');
    }

    public function toggleStatus(PopularTopic $popularTopic): RedirectResponse
    {
        $popularTopic->update([
            'is_active' => ! $popularTopic->is_active,
        ]);

        $status = $popularTopic->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Topik populer berhasil {$status}.");
    }
}
