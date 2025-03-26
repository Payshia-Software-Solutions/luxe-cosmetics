import React from 'react';
import { Star, Sparkles } from 'lucide-react';
import Image from 'next/image';
import Link from 'next/link';

import { products } from '@/data/products';

export default function TrendingProducts() {
  return (
    <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-2 mb-8">
          <Sparkles className="h-8 w-8 text-pink-600" />
          <h2 className="text-5xl font-bold text-gray-900 dark:text-white">
            Trending Now
          </h2>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {products.map((product) => (
            <div
              key={product.id}
              className="group relative  rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl"
            >
              <div className="aspect-w-1 aspect-h-1">
                <Image
                  src={product.images[0]}
                  alt={product.name}
                  className="w-full h-80 object-cover transform transition-transform group-hover:scale-105"
                  width={1000}
                  height={1000}
                />
                <div className="absolute top-4 right-4 bg-pink-600 text-white px-3 py-1 rounded-full text-sm">
                  {Math.floor(Math.random() * 1000) + 1} sold
                </div>
              </div>
              <div className="p-6 bg-white dark:bg-[#1c3c34] ">
                <h3 className="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                  {product.name}
                </h3>
                <div className="flex items-center mb-4">
                  <Star className="h-5 w-5 text-yellow-400 fill-current" />
                  <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                    {product.rating}
                  </span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-2xl font-bold text-gray-900 dark:text-white">
                    {new Intl.NumberFormat('en-LK', { style: 'currency', currency: 'LKR' }).format(product.price)}
                  </span>

                  <Link href={`/products/${product.slug}`}>
                    <button className="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2 rounded-full text-sm font-medium transition-colors">
                      View Details
                    </button>
                  </Link>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}