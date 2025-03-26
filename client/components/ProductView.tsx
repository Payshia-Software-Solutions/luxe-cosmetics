"use client";
import React, { useState } from 'react';
import { Star, Heart, Share2, Minus, Plus, ShoppingCart, ChevronRight } from 'lucide-react';

import type { Product } from '@/data/products';
import Link from 'next/link';
import Image from 'next/image';

interface ProductViewProps {
  product: Product;
}

export default function ProductView({ product }: ProductViewProps) {
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const [activeTab, setActiveTab] = useState('description');

  // const relatedProducts = products
  //   .filter(p => p.category === product.category && p.id !== product.id)
  //   .slice(0, 3);

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      {/* Breadcrumbs */}
      <nav className="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
        {product.breadcrumbs.map((item, index) => (
          <React.Fragment key={index}>
            <Link href="#" className="hover:text-pink-600">
              {item}
            </Link>
            {index < product.breadcrumbs.length - 1 && <ChevronRight className="h-4 w-4" />}
          </React.Fragment>
        ))}
      </nav>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
        {/* Product Images */}
        <div className="space-y-4">
          <div className="aspect-w-1 aspect-h-1 rounded-2xl overflow-hidden">
            <Image
              src={product.images[selectedImage]}
              alt={product.name}
              className="w-full h-[600px] object-cover"
              width={100}
              height={100}
            />
          </div>
          <div className="grid grid-cols-3 gap-4">
            {product.images.map((image, index) => (
              <button
                key={index}
                onClick={() => setSelectedImage(index)}
                className={`rounded-lg overflow-hidden border-2 ${selectedImage === index ? 'border-pink-600' : 'border-transparent'
                  }`}
              >
                <Image src={image} alt="" className="w-full h-24 object-cover"
                  width={100}
                  height={100} />
              </button>
            ))}
          </div>
        </div>

        {/* Product Info */}
        <div className="space-y-6">
          <span className="inline-block bg-pink-100 dark:bg-pink-900 text-pink-600 dark:text-pink-300 px-3 py-1 rounded-full text-sm font-medium">
            {product.category}
          </span>

          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
            {product.name}
          </h1>

          <div className="flex items-center space-x-4">
            <div className="flex items-center">
              <Star className="h-5 w-5 text-yellow-400 fill-current" />
              <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                {product.rating} ({product.reviews.length} reviews)
              </span>
            </div>
            <button className="text-gray-400 hover:text-pink-600 transition-colors">
              <Heart className="h-6 w-6" />
            </button>
            <button className="text-gray-400 hover:text-pink-600 transition-colors">
              <Share2 className="h-6 w-6" />
            </button>
          </div>

          <div className="text-3xl font-bold text-gray-900 dark:text-white">
            ${product.price}
          </div>

          <p className="text-gray-600 dark:text-gray-300">
            {product.description}
          </p>

          <div className="space-y-4">
            <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
              Key Benefits
            </h3>
            <ul className="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-300">
              {product.benefits.map((benefit, index) => (
                <li key={index}>{benefit}</li>
              ))}
            </ul>
          </div>

          <div className="pt-6">
            <div className="flex items-center space-x-6">
              <div className="flex items-center border border-gray-300 rounded-full">
                <button
                  onClick={() => setQuantity(Math.max(1, quantity - 1))}
                  className="p-2 hover:text-pink-600 transition-colors"
                >
                  <Minus className="h-5 w-5" />
                </button>
                <span className="w-12 text-center">{quantity}</span>
                <button
                  onClick={() => setQuantity(quantity + 1)}
                  className="p-2 hover:text-pink-600 transition-colors"
                >
                  <Plus className="h-5 w-5" />
                </button>
              </div>
              <button className="flex-1 bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-full font-medium flex items-center justify-center space-x-2 transition-colors">
                <ShoppingCart className="h-5 w-5" />
                <span>Add to Cart</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Product Details Tabs */}
      <div className="border-b border-gray-200 dark:border-gray-700 mb-8">
        <nav className="flex space-x-8">
          {['description', 'specifications', 'reviews'].map((tab) => (
            <button
              key={tab}
              onClick={() => setActiveTab(tab)}
              className={`py-4 px-1 border-b-2 font-medium text-sm ${activeTab === tab
                ? 'border-pink-600 text-pink-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                }`}
            >
              {tab.charAt(0).toUpperCase() + tab.slice(1)}
            </button>
          ))}
        </nav>
      </div>

      {/* Tab Content */}
      <div className="mb-16">
        {activeTab === 'description' && (
          <div className="prose dark:prose-invert max-w-none">
            <p className="whitespace-pre-line">{product.longDescription}</p>
          </div>
        )}

        {activeTab === 'specifications' && (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {Object.entries(product.specifications).map(([key, value]) => (
              <div key={key} className="border-b dark:border-gray-700 pb-4">
                <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">{key}</dt>
                <dd className="mt-1 text-sm text-gray-900 dark:text-white">{value}</dd>
              </div>
            ))}
          </div>
        )}

        {activeTab === 'reviews' && (
          <div className="space-y-8">
            {product.reviews.map((review) => (
              <div key={review.id} className="border-b dark:border-gray-700 pb-8">
                <div className="flex items-center justify-between mb-4">
                  <div>
                    <div className="flex items-center">
                      <div className="flex items-center">
                        {[...Array(5)].map((_, i) => (
                          <Star
                            key={i}
                            className={`h-5 w-5 ${i < review.rating
                              ? 'text-yellow-400 fill-current'
                              : 'text-gray-300'
                              }`}
                          />
                        ))}
                      </div>
                      <h4 className="ml-2 text-sm font-bold text-gray-900 dark:text-white">
                        {review.title}
                      </h4>
                    </div>
                    <p className="mt-1 text-sm text-gray-500">
                      By {review.user} on {review.date}
                    </p>
                  </div>
                  {review.verified && (
                    <span className="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                      Verified Purchase
                    </span>
                  )}
                </div>
                <p className="text-gray-600 dark:text-gray-300">{review.comment}</p>
                <div className="mt-4 flex items-center space-x-4">
                  <button className="text-sm text-gray-500 hover:text-gray-700">
                    Helpful ({review.helpful})
                  </button>
                  <button className="text-sm text-gray-500 hover:text-gray-700">
                    Report
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Related Products */}
      <div>
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-8">
          Related Products
        </h2>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* {relatedProducts.map((product) => (
            <Link
              to={`/product/${product.slug}`}
              key={product.id}
              className="group bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden"
            >
              <div className="aspect-w-1 aspect-h-1">
                <img
                  src={product.images[0]}
                  alt={product.name}
                  className="w-full h-64 object-cover transform transition-transform group-hover:scale-105"
                />
              </div>
              <div className="p-4">
                <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2">
                  {product.name}
                </h3>
                <div className="flex items-center mb-2">
                  <Star className="h-5 w-5 text-yellow-400 fill-current" />
                  <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                    {product.rating}
                  </span>
                </div>
                <div className="flex items-center justify-between">
                  <span className="text-xl font-bold text-gray-900 dark:text-white">
                    ${product.price}
                  </span>
                  <span className="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                    View Details
                  </span>
                </div>
              </div>
            </Link>
          ))} */}
        </div>
      </div>
    </div>
  );
}