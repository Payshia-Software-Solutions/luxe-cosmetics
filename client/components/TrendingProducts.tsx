"use client";

import React, { useState, useEffect } from "react";
import { Sparkles, Loader2, AlertCircle } from "lucide-react";
import { Swiper, SwiperSlide } from "swiper/react";
import { Autoplay, Navigation } from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";

import TrendingProductCard from "./common/TrendingProductCard";
import TitleHeader from "@/components/TitleHeader";
import { Product } from "@/types/product";

export default function TrendingProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    // Move productIds inside the effect since it's static
    const productIds = [1, 3, 5, 7, 8];

    const fetchProducts = async () => {
      try {
        setLoading(true);
        setError(null);

        const res = await fetch(`${process.env.NEXT_PUBLIC_API_URL}/products`); //

        if (!res.ok) {
          throw new Error(`HTTP error! status: ${res.status}`);
        }

        const data = await res.json();

        // Filter and sort products by productIds order
        const filteredProducts = data
          .filter((product: Product) => productIds.includes(product.product_id))
          .sort(
            (a: Product, b: Product) =>
              productIds.indexOf(a.product_id) -
              productIds.indexOf(b.product_id)
          );

        setProducts(filteredProducts);
      } catch (error) {
        setError("Failed to fetch trending products");
        console.error("Failed to fetch products:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []); // Empty dependency array is now correct

  // Loading state
  if (loading) {
    return (
      <section className="py-8 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-2 mb-8">
            <Sparkles className="h-8 w-8 text-pink-600" />
            <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
              Trending Now
            </h2>
          </div>

          <div className="flex items-center justify-center py-12">
            <div className="flex flex-col items-center gap-4">
              <Loader2 className="h-8 w-8 animate-spin text-pink-600" />
              <p className="text-gray-600 dark:text-gray-400">
                Loading trending products...
              </p>
            </div>
          </div>
        </div>
      </section>
    );
  }

  // Error state
  if (error) {
    return (
      <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-2 mb-8">
            <Sparkles className="h-8 w-8 text-pink-600" />
            <TitleHeader
              title="Trending Products"
              description="Discover what's hot at Paris Beauty. Shop trending cosmetics and beauty must-haves loved by our customers."
            />
          </div>

          <div className="flex items-center justify-center py-12">
            <div className="flex flex-col items-center gap-4 text-center">
              <AlertCircle className="h-8 w-8 text-red-500" />
              <p className="text-red-600 dark:text-red-400">{error}</p>
              <button
                onClick={() => window.location.reload()}
                className="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg transition-colors"
              >
                Try Again
              </button>
            </div>
          </div>
        </div>
      </section>
    );
  }

  // Empty state
  if (products.length === 0) {
    return (
      <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-2 mb-8">
            <Sparkles className="h-8 w-8 text-pink-600" />
            <TitleHeader
              title="Trending Products"
              description="Discover what's hot at Paris Beauty. Shop trending cosmetics and beauty must-haves loved by our customers."
            />
          </div>

          <div className="flex items-center justify-center py-12">
            <p className="text-gray-600 dark:text-gray-400">
              No trending products found.
            </p>
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="py-16 bg-[#fff0e9] dark:bg-[#1e1e1e] transition-colors">
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <TitleHeader
          title="Trending Products"
          description="Discover what's hot at Paris Beauty. Shop trending cosmetics and beauty must-haves loved by our customers."
        />

        <Swiper
          modules={[Autoplay, Navigation]}
          autoplay={{
            delay: 3000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }}
          navigation={false}
          spaceBetween={20}
          loop={products.length > 3}
          slidesPerView={1.1}
          breakpoints={{
            640: { slidesPerView: 1.5 },
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 },
          }}
          className="py-12"
        >
          {products.slice(0, 6).map((product) => (
            <SwiperSlide key={product.product_id}>
              <TrendingProductCard
                product={product}
                salesCount={Math.floor(Math.random() * (500 - 50 + 1)) + 50}
              />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </section>
  );
}
