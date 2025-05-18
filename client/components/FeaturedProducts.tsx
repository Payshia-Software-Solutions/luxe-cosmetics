"use client";

import React, { useState, useEffect } from "react";
import axios from "axios";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";
import { ShoppingBag } from "lucide-react";
import { ToastContainer, toast } from "react-toastify";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "react-toastify/dist/ReactToastify.css";

import ProductCard from "./common/ProductCard";
import { useCart } from "./CartContext";
import { Product } from "@/types/product";

export default function FeaturedProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [wishlist, setWishlist] = useState<number[]>([]);
  
  // Use cart functions only (removed unused cartItems)
  const { addToCart, openCart, getCartCount } = useCart();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_URL}/products`
        );

        let productsData = response.data;

        if (!Array.isArray(productsData)) {
          if (productsData?.products) {
            productsData = productsData.products;
          } else if (productsData?.data) {
            productsData = productsData.data;
          } else if (productsData?.results) {
            productsData = productsData.results;
          } else if (productsData?.product_id) {
            productsData = [productsData];
          } else {
            throw new Error("Unexpected response format");
          }
        }

        if (!Array.isArray(productsData)) {
          throw new Error("Could not extract products array from response");
        }

        const validProducts = productsData.filter((product): product is Product => {
          return (
            typeof product === "object" &&
            product !== null &&
            "product_id" in product &&
            ("product_name" in product || "display_name" in product)
          );
        });
        
        setProducts(validProducts);
        setError(null);
      } catch (err: unknown) {
        // Properly use error to prevent ESLint warning
        const message = err instanceof Error ? err.message : "Unknown error occurred";
        setError("Failed to load products. " + message);
        setProducts([]);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  const handleAddToCart = (productId: number) => {
    const productToAdd = products.find(product => product.product_id === productId);
    if (!productToAdd) return;

    const newCartItem = {
      id: productId,
      name: productToAdd.display_name || productToAdd.product_name,
      price: productToAdd.selling_price,
      quantity: 1,
      image: `/assets/product/${productToAdd.image_path}`,
    };

    addToCart(newCartItem);
    openCart();
    toast.success(`${productToAdd.display_name || productToAdd.product_name} added to cart!`);
  };

  const handleToggleWishlist = (productId: number) => {
    setWishlist((prev) =>
      prev.includes(productId)
        ? prev.filter((id) => id !== productId)
        : [...prev, productId]
    );
  };

  if (loading) {
    return (
      <section className="py-16 dark:bg-[#1e1e1e] bg-[#fff0e9] transition-colors">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white border-b pb-2 mb-2">
            Featured Products
          </h2>
          <div className="py-12 flex justify-center items-center">
            <div className="animate-pulse text-lg">Loading products...</div>
          </div>
        </div>
      </section>
    );
  }

  if (error) {
    return (
      <section className="py-16 dark:bg-[#1e1e1e] bg-[#fff0e9] transition-colors">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white border-b pb-2 mb-2">
            Featured Products
          </h2>
          <div className="py-12 text-center text-red-600 dark:text-red-400">
            {error}
          </div>
        </div>
      </section>
    );
  }

  return (
    <section className="py-16 dark:bg-[#1e1e1e] bg-[#fff0e9] transition-colors">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between items-center border-b pb-2 mb-2">
          <h2 className="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white">
            Featured Products
          </h2>
          <button
            onClick={openCart}
            className="flex items-center gap-2 bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-full"
          >
            <ShoppingBag className="h-5 w-5" />
            <span>Cart ({getCartCount()})</span>
          </button>
        </div>

        {products.length > 0 ? (
          <Swiper
            modules={[Navigation, Pagination, Autoplay]}
            spaceBetween={5}
            slidesPerView={1.5}
            breakpoints={{
              640: { slidesPerView: 2.5 },
              1024: { slidesPerView: 3.5, spaceBetween: 15 },
              1280: { slidesPerView: 4.5, spaceBetween: 20 },
            }}
            navigation={false}
            pagination={{ clickable: true }}
            autoplay={{
              delay: 3000,
              disableOnInteraction: false,
              pauseOnMouseEnter: true,
            }}
            loop={products.length > 4}
            className="py-12"
          >
            {products.map((product) => (
              <SwiperSlide
                className="mb-8"
                key={product.product_id}>
                <ProductCard
                  product={product}
                  onAddToCart={handleAddToCart}
                  onToggleWishlist={handleToggleWishlist}
                  isInWishlist={wishlist.includes(product.product_id)}
                />
              </SwiperSlide>
            ))}
          </Swiper>
        ) : (
          <div className="py-12 text-center">
            No featured products available at this time.
          </div>
        )}
      </div>

      <ToastContainer />
    </section>
  );
}