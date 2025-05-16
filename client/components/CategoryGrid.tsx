import React from 'react';
import { Star } from 'lucide-react';
import Image from 'next/image';
import Link from 'next/link';
import { products } from '@/data/products';
import { CategoryViewProps } from '@/types/CategoryViewProps';

export default function CategoryGrid({ Category }: CategoryViewProps) {
  return (
    <section className="py-16 bg-white dark:bg-[#1e1e1e] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-2 mb-8">
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white border-b-2 pb-2">
            {Category}
          </h2>
        </div>

        <div className="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-y-4 md:gap-x-8">
          {products.map((product) => (
            <Link key={product.id} href={`/products/${product.slug}`}>
              <div className="bg-white dark:bg-[#1c3c34] rounded-lg shadow overflow-hidden transition-transform hover:shadow-lg mt-3">
                <div className="aspect-w-1 aspect-h-1">
                  <Image
                    src={product.images[0]}
                    alt={product.name}
                    className="w-full h-48 object-cover"
                    width={1000}
                    height={1000}
                  />
                </div>
                <div className="p-4">
                  <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2  line-clamp-1">
                    {product.name}
                  </h3>
                  <div className="flex items-center mb-2">
                    <Star className="h-5 w-5 text-yellow-400 fill-current" />
                    <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                      {product.rating}
                    </span>
                  </div>
                  <div className="flex flex-col md:flex-row items-center justify-between">
                    <span className="text-xl font-bold text-gray-900 dark:text-white mb-2 md:mb-0">
                      ${product.price}
                    </span>
                    <button className="bg-pink-600 hover:bg-pink-700 w-full md:w-auto text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                      Add to Cart
                    </button>
                  </div>

                </div>
              </div>
            </Link>
          ))}
        </div>
      </div>
    </section>
  );
}