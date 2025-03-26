import React from 'react';
import Hero from '@/components/Hero';
import FeaturedProducts from '@/components/FeaturedProducts';
import TrendingProducts from '@/components/TrendingProducts';
import { Metadata } from 'next';

export const metadata: Metadata = {
  title: "Cosmetic Shop | Premium Beauty & Skincare Products Online",
  description: "Discover a wide range of premium beauty and skincare products at our Cosmetic Shop. Shop for makeup, skincare, haircare, and more with fast delivery and expert advice.",
  keywords: "cosmetic shop, beauty products, skincare, makeup, skincare products, premium cosmetics, online beauty store, skincare online, makeup online, beauty essentials",
  robots: "index, follow",
  viewport: "width=device-width, initial-scale=1",
};


export default function Home() {
  return (
    <div>
      <Hero />
      <FeaturedProducts />
      <TrendingProducts />
    </div>
  );
}
