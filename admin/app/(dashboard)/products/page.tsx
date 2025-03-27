// app/products/page.tsx
'use client';

import { useState } from 'react';
import { Plus } from 'lucide-react';

export default function Products() {
    const [products] = useState([
        { id: 1, name: 'T-Shirt', price: 19.99, stock: 50 },
        { id: 2, name: 'Jeans', price: 49.99, stock: 30 },
    ]);

    return (
        <div>
            <div className="flex justify-between items-center mb-6">
                <h2 className="text-2xl font-bold">Products</h2>
                <button className="flex items-center px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    <Plus className="w-5 h-5 mr-2" />
                    Add Product
                </button>
            </div>

            <div className="bg-white rounded-lg shadow overflow-hidden">
                <table className="min-w-full">
                    <thead className="bg-gray-50">
                        <tr>
                            <th className="p-4 text-left">Name</th>
                            <th className="p-4 text-left">Price</th>
                            <th className="p-4 text-left">Stock</th>
                            <th className="p-4 text-left">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((product) => (
                            <tr key={product.id} className="border-t">
                                <td className="p-4">{product.name}</td>
                                <td className="p-4">${product.price.toFixed(2)}</td>
                                <td className="p-4">{product.stock}</td>
                                <td className="p-4">
                                    <button className="text-blue-500 hover:underline mr-4">Edit</button>
                                    <button className="text-red-500 hover:underline">Delete</button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}