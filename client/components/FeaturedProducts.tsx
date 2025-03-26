"use client";

import React from 'react';
import { Star } from 'lucide-react';
import Link from 'next/link';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import Image from 'next/image';

const products = [
  {
    id: '1',
    slug: 'natural-glow-serum',
    name: 'Natural Glow Serum',
    price: 49.99,
    rating: 4.8,
    image: 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: '2',
    slug: 'hydrating-face-cream',
    name: 'Hydrating Face Cream',
    price: 39.99,
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1611930022073-b7a4ba5fcccd?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: '3',
    slug: 'rose-water-toner',
    name: 'Rose Water Toner',
    price: 24.99,
    rating: 4.7,
    image: 'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: '4',
    slug: 'vitamin-c-moisturizer',
    name: 'Vitamin C Moisturizer',
    price: 54.99,
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  },
  {
    id: '5',
    slug: 'vitamin-c-moisturizer',
    name: 'Vitamin C Moisturizer',
    price: 54.99,
    rating: 4.9,
    image: 'https://images.unsplash.com/photo-1608248543803-ba4f8c70ae0b?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'
  }
];

export default function FeaturedProducts() {
  return (
    <section className="py-16 dark:bg-[#1c3c34] bg-[#fff0e9] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="text-3xl font-bold text-gray-900 dark:text-white mb-8">
          Featured Products
        </h2>

        <Swiper
          modules={[Navigation, Pagination]}
          spaceBetween={20}
          slidesPerView={1.5}
          breakpoints={{
            640: { slidesPerView: 2.5 },
            1024: { slidesPerView: 3.5 },
            1280: { slidesPerView: 4.5 }
          }}
          navigation={false}
          pagination={{ clickable: true }}
          className="py-12"
        >
          {products.map((product) => (
            <SwiperSlide key={product.id}>
              <Link href={`/products/${product.slug}`}>
                <div className="bg-white dark:bg-[#1e1e1e] rounded-lg shadow overflow-hidden transition-transform hover:shadow-lg mt-3 mb-12">
                  <div className="aspect-w-1 aspect-h-1">
                    <Image
                      src={product.image}
                      alt={product.name}
                      className="w-full h-64 object-cover"
                      width={100}
                      height={100}
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
                      <button className="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors">
                        Add to Cart
                      </button>
                    </div>
                  </div>
                </div>
              </Link>
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
}
