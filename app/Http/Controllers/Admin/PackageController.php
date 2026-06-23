<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Package;
use Illuminate\Support\Facades\Storage;

use App\Models\Category;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::with('category')->latest();

        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $packages = $query->paginate(10)->withQueryString();
        $categories = Category::all();

        // Get top 2 packages by booking count for Hot Sale tag
        $hotSalePackageIds = Package::withCount('bookings')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->take(2)
            ->pluck('id')
            ->toArray();

        return view('admin.packages.index', compact('packages', 'categories', 'hotSalePackageIds'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.packages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'gap_minutes' => 'nullable|integer|min:0',
            'terms_and_conditions' => 'nullable|string',
            'tag' => 'nullable|string|in:premium,luxury',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:10240'
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.webp';  
            $imagePath = public_path('images/paket/' . $imageName);
            
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($request->file('image')->getRealPath());
            $image->scaleDown(width: 1200);
            $image->save($imagePath);
            
            $validated['image'] = $imageName;
        }

        $validated['is_active'] = $request->has('is_active');
        
        if (!$request->has('has_discount')) {
            $validated['discount_price'] = null;
        }

        Package::create($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        $categories = Category::all();
        return view('admin.packages.edit', compact('package', 'categories'));
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'required|integer|min:0',
            'gap_minutes' => 'nullable|integer|min:0',
            'terms_and_conditions' => 'nullable|string',
            'tag' => 'nullable|string|in:premium,luxury',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:10240'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($package->image && file_exists(public_path('images/paket/' . $package->image))) {
                unlink(public_path('images/paket/' . $package->image));
            }
            
            $imageName = time() . '.webp';  
            $imagePath = public_path('images/paket/' . $imageName);
            
            $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $manager->decode($request->file('image')->getRealPath());
            $image->scaleDown(width: 1200);
            $image->save($imagePath);
            
            $validated['image'] = $imageName;
        }

        $validated['is_active'] = $request->has('is_active');

        if (!$request->has('has_discount')) {
            $validated['discount_price'] = null;
        }

        $package->update($validated);

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package)
    {
        if ($package->image && file_exists(public_path('images/paket/' . $package->image))) {
            unlink(public_path('images/paket/' . $package->image));
        }
        
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Paket berhasil dihapus.');
    }
}
