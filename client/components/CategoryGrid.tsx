"use client";
import React, { useState, useEffect } from "react";
import axios from "axios";
import ProductCard from "@/components/common/ProductCard"; // Import your ProductCard component

import { Product, ProductCategoryViewProps } from "@/types/product"; // Import the Product interface
import CollectionHeader from "@/components/CollectionHeader";

export default function ProductCategoryView({
  searchTerm = "serum",
  initialData = null,
  toggleBgColor = 0,
}: ProductCategoryViewProps) {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [wishlist, setWishlist] = useState<number[]>([]);

  useEffect(() => {
    // If we have initial data, use it
    if (initialData) {
      if (initialData.success && Array.isArray(initialData.data)) {
        setProducts(initialData.data);
      }
      setLoading(false);
      return;
    }

    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_URL}/products/search/category?term=${searchTerm}`
        ); //

        if (
          response.data &&
          response.data.success &&
          Array.isArray(response.data.data)
        ) {
          setProducts(response.data.data);
        } else {
          setProducts([]);
        }
        setLoading(false);
      } catch (err) {
        console.error("Error fetching products:", err);
        setError("Failed to load products. Please try again later.");
        setLoading(false);
      }
    };

    fetchProducts();
  }, [searchTerm, initialData]);

  // Handle adding product to cart
  const handleAddToCart = (productId: number) => {
    console.log(`Added product ${productId} to cart`);
    // Here you would implement your cart logic
    // For example, dispatch to a cart context or store
  };

  // Handle toggling product in wishlist
  const handleToggleWishlist = (productId: number) => {
    setWishlist((prevWishlist) => {
      if (prevWishlist.includes(productId)) {
        return prevWishlist.filter((id) => id !== productId);
      } else {
        return [...prevWishlist, productId];
      }
    });
  };

  // Check if a product is in the wishlist
  const isInWishlist = (productId: number) => wishlist.includes(productId);

  return (
    <section
      className={`py-16 transition-colors ${
        toggleBgColor
          ? "bg-[#fff0e9] dark:bg-[#1e1e1e]"
          : "bg-white dark:bg-[#161313]"
      }`}
    >
      <div className="container mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center gap-2 mb-8">
          <CollectionHeader
            title={searchTerm.charAt(0).toUpperCase() + searchTerm.slice(1)}
            description=""
          />
        </div>

        {loading ? (
          <div className="flex justify-center items-center h-64">
            <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-pink-600"></div>
          </div>
        ) : error ? (
          <div className="text-center py-10">
            <p className="text-red-500">{error}</p>
          </div>
        ) : products.length === 0 ? (
          <div className="text-center py-10">
            <p className="text-gray-600 dark:text-gray-300">
              No products found for &quot;{searchTerm}&quot;.
            </p>
          </div>
        ) : (
          <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            {products.map((product) => (
              <div key={product.product_id} className="h-full">
                <ProductCard
                  product={product}
                  onAddToCart={handleAddToCart}
                  onToggleWishlist={handleToggleWishlist}
                  isInWishlist={isInWishlist(product.product_id)}
                />
              </div>
            ))}
          </div>
        )}
      </div>
    </section>
  );
}
