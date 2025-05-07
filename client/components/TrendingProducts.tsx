'use client';

import React from 'react';
import { Sparkles } from 'lucide-react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination, Navigation } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

import TrendingProductCard from './common/TrendingProductCard';
import { products } from '@/data/products';

export default function TrendingProducts() {
  return (
    <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-2 mb-8">
          <Sparkles className="h-8 w-8 text-pink-600" />
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
            Trending Now
          </h2>
        </div>

        <Swiper
          modules={[Autoplay, Pagination, Navigation]}
          autoplay={{
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }}
          pagination={{ clickable: true }}
          navigation={false}
          spaceBetween={20}
          loop={true}
          slidesPerView={1.1}
          breakpoints={{
            640: { slidesPerView: 1.5 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
          }}
          className="pb-12"
        >
          {products.slice(0, 6).map((product) => (
            <SwiperSlide key={product.id}>
              <TrendingProductCard product={product} />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
}
