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

interface Product {
  product_id: number;
  product_code: string;
  product_name: string;
  slug: string;
  display_name: string;
  name_si: string;
  name_ti: string;
  print_name: string;
  section_id: number;
  department_id: number;
  category_id: number;
  brand_id: number;
  measurement: string;
  reorder_level: number;
  lead_days: number;
  cost_price: number;
  selling_price: number;
  minimum_price: number;
  wholesale_price: number;
  price_2: number;
  item_type: string;
  item_location: string;
  image_path: string;
  created_by: string;
  created_at: string;
  active_status: number;
  generic_id: string | null;
  supplier_list: string;
  size_id: number;
  color_id: number | null;
  product_description: string;
  how_to_use: string | null;
  recipe_type: string;
  barcode: string;
  expiry_good: number;
  location_list: string;
  opening_stock: number;
  special_promo: number;
  special_promo_type: string;
  special_promo_message: string | null;
  rating: string;
  review: number;
  long_description: string;
  benefits: string;
  specifications: string;
  category: string;
  meta_description: string | null;
  reviews: string | null;
  hover_image: string | null;
}

export default function FeaturedProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [wishlist, setWishlist] = useState<number[]>([]);
  
  // Use the cart context instead of local state
  const { cartItems, addToCart, openCart, getCartCount } = useCart();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await axios.get("http://localhost/luxe-cosmetics/server/products");

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

        const validProducts = productsData.filter((product: any) => {
          return product && typeof product === "object" && product.product_id && (product.product_name || product.display_name);
        });

        setProducts(validProducts);
        setError(null);
      } catch (err) {
        setError("Failed to load products. Please try again later.");
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
      id: productId.toString(),
      name: productToAdd.display_name || productToAdd.product_name,
      price: productToAdd.selling_price,
      quantity: 1,
      image: `/assets/product/${productToAdd.image_path}`,
    };

    // Add to cart using context function
    addToCart(newCartItem);
    
    // Open cart
    openCart();

    // Show success toast notification
    toast.success(`${productToAdd.display_name || productToAdd.product_name} added to cart!`);
  };

  const handleToggleWishlist = (productId: number) => {
    setWishlist((prev) =>
      prev.includes(productId) ? prev.filter((id) => id !== productId) : [...prev, productId]
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

        {Array.isArray(products) && products.length > 0 ? (
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
              <SwiperSlide key={product.product_id}>
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
          <div className="py-12 text-center">No featured products available at this time.</div>
        )}
      </div>

      {/* Cart is conditionally rendered by the context */}
      <ToastContainer />
    </section>
  );
}