<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'route' => route('admin.dashboard'),
    ],
    [
        'name' => 'Productos',
        'route' => route('admin.products.index'),
    ],
    [
        'name' => $product->name,
    ],
]">

    @livewire('admin.products.product-edit', ['product' => $product], key('product-edit-' . $product->id))

    @livewire('admin.products.product-variants', ['product' => $product], key('variants-' . $product->id))

</x-admin-layout>