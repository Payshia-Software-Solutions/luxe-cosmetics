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
import { products } from '@/data/products';

export default function FeaturedProducts() {
  return (
    <section className="py-16 dark:bg-[#1e1e1e] bg-[#fff0e9] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h2 className="text-5xl font-bold text-gray-900 dark:text-white border-b pb-2 mb-2">
          Featured Products
        </h2>

        <Swiper
          modules={[Navigation, Pagination]}
          spaceBetween={5}
          slidesPerView={1.5}
          breakpoints={{
            640: { slidesPerView: 2.5 },
            1024: {
              slidesPerView: 3.5,
              spaceBetween: 15
            },
            1280: {
              slidesPerView: 4.5,
              spaceBetween: 20
            }
          }}
          navigation={false}
          pagination={{ clickable: true }}
          className="py-12"
        >
          {products.map((product) => (
            <SwiperSlide key={product.id}>
              <Link href={`/products/${product.slug}`}>
                <div className="bg-white dark:bg-[#1c3c34] rounded-lg shadow overflow-hidden transition-transform hover:shadow-lg mt-3 mb-12">
                  <div className="aspect-w-1 aspect-h-1">
                    <Image
                      src={product.images[0]}
                      alt={product.name}
                      className="w-full h-64 object-cover"
                      width={1000}
                      height={1000}
                    />
                  </div>
                  <div className="p-4">
                    <h3 className="text-lg font-medium text-gray-900 dark:text-white mb-2 line-clamp-1">
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
