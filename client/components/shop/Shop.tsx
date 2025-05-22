"use client";

import React, { useState, useEffect, useRef } from "react";
import axios from "axios";
import ProductCard from "../common/ProductCard";
import SideBar from "./SideBar";
import { motion } from "framer-motion";

import { ShoppingBag } from "lucide-react";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import { useCart } from "../CartContext"; // Import the cart context
import { Filters } from "@/types/shop"; // Import the Filters type
import { Product } from "@/types/product"; // Import the Product type

const Shop: React.FC = () => {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);
  const [wishlist, setWishlist] = useState<number[]>([]);
  const [filterActive, setFilterActive] = useState(false);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
  const [filters, setFilters] = useState<Filters>({
    categories: [],
    brands: [],
    ratings: [],
    onSale: false,
    priceRange: [0, 300],
    sort: "",
  });
  const [isSearching, setIsSearching] = useState(false);
  const [isSorting, setIsSorting] = useState(false);
  const prevSortRef = useRef<string | undefined>("");

  const { addToCart, openCart, getCartCount } = useCart();

  useEffect(() => {
    const fetchProducts = async () => {
      try {
        setLoading(true);
        const response = await axios.get(
          `${process.env.NEXT_PUBLIC_API_URL}/products` 
              
         
        );

        if (
          response.data &&
          response.data.success &&
          Array.isArray(response.data.data)
        ) {
          setProducts(response.data.data);
        } else if (Array.isArray(response.data)) {
          setProducts(response.data);
        } else {
          setError("Invalid data format received from server");
          console.error("Invalid data format:", response.data);
        }
      } catch (err) {
        setError("Failed to fetch products. Please try again later.");
        console.error("Error fetching products:", err);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  useEffect(() => {
    if (prevSortRef.current !== filters.sort) {
      setIsSorting(true);

      const timer = setTimeout(() => {
        setIsSorting(false);
      }, 800);

      prevSortRef.current = filters.sort;

      return () => clearTimeout(timer);
    }
  }, [filters.sort]);

  useEffect(() => {
    const applyAllFilters = async () => {
      try {
        setIsSearching(true);
        let results: Product[] = [];

        if (filters.categories && filters.categories.length > 0) {
          const categoryPromises = filters.categories.map(
            (category: string | number | boolean) =>
              axios.get(
                `${process.env.NEXT_PUBLIC_API_URL}/products/search/category?term=${encodeURIComponent(
                  category
                )}`
              )
          );

          const responses = await Promise.all(categoryPromises);

          const categoryResults = responses.flatMap(
            (response: { data: { success: boolean; data: Product[] } }) => {
              if (
                response.data &&
                response.data.success &&
                Array.isArray(response.data.data)
              ) {
                return response.data.data;
              } else if (Array.isArray(response.data)) {
                return response.data;
              }
              return [];
            }
          );

          results = Array.from(
            new Map(
              (categoryResults as Product[]).map((item: Product) => [
                item.product_id,
                item,
              ])
            ).values()
          );
        } else {
          results = [...products];
        }

        if (filters.priceRange) {
          results = results.filter(
            (product) =>
              product.selling_price >= filters.priceRange![0] &&
              product.selling_price <= filters.priceRange![1]
          );
        }

        // Apply brand filter
        if (filters.brands && filters.brands.length > 0) {
          results = results.filter((product) =>
            filters.brands!.some(
              (brand: string) =>
                product.brand_id.toString() === brand ||
                product.product_name.includes(brand)
            )
          );
        }

        // Apply rating filter
        if (filters.ratings && filters.ratings.length > 0) {
          results = results.filter((product) => {
            const productRating = parseFloat(product.rating.toString());
            return filters.ratings!.some(
              (rating: number) => productRating >= rating
            );
          });
        }

        // Apply sale filter
        if (filters.onSale) {
          results = results.filter((product) => product.special_promo === 1);
        }

        // Step 3: Apply sorting (IMPORTANT: Sorting happens last)
        if (filters.sort) {
          switch (filters.sort) {
            case "price_asc":
              results.sort((a, b) => a.selling_price - b.selling_price);
              break;
            case "price_desc":
              results.sort((a, b) => b.selling_price - a.selling_price);
              break;
            case "newest":
              results.sort(
                (a, b) =>
                  new Date(b.created_at).getTime() -
                  new Date(a.created_at).getTime()
              );
              break;
            case "oldest":
              results.sort(
                (a, b) =>
                  new Date(a.created_at).getTime() -
                  new Date(b.created_at).getTime()
              );
              break;
            default:
              // No sorting
              break;
          }
        }

        // Finally, update filtered products
        setFilteredProducts(results);
      } catch (err) {
        console.error("Error applying filters:", err);
        setError("Failed to apply filters. Please try again.");
      } finally {
        setIsSearching(false);
      }
    };

    // Apply all filters whenever any filter changes
    applyAllFilters();
  }, [filters, products]);

  // Updated handleAddToCart function using the CartContext
  const handleAddToCart = (productId: number) => {
    const productToAdd = filteredProducts.find(
      (product) => product.product_id === productId
    );

    if (!productToAdd) return;

    const newCartItem = {
      id: productId, // Keep as number, don't convert to string
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
    toast.success(
      `${productToAdd.display_name || productToAdd.product_name} added to cart!`
    );
  };

  const handleToggleWishlist = (productId: number) => {
    setWishlist((prev) =>
      prev.includes(productId)
        ? prev.filter((id) => id !== productId)
        : [...prev, productId]
    );
  };

  const toggleFilters = () => {
    setFilterActive(!filterActive);
  };

  const handleFilterChange = (filterType: string, value: unknown) => {
    if (filterType === "resetAll") {
      setFilters({
        categories: [],
        brands: [],
        ratings: [],
        onSale: false,
        priceRange: [0, 300],
        sort: "",
      });
      return;
    }

    setFilters((prev: Filters) => ({
      ...prev,
      [filterType]: value,
    }));
  };

  // Staggered animation for product cards
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1,
      },
    },
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.3 },
    },
  };

  // Sort indicator component
  const SortIndicator = () => {
    if (!filters.sort) return null;

    const getSortIcon = () => {
      switch (filters.sort) {
        case "price_asc":
          return "↑";
        case "price_desc":
          return "↓";
        case "newest":
          return "★";
        case "oldest":
          return "☆";
        default:
          return "•";
      }
    };

    const getSortLabel = () => {
      switch (filters.sort) {
        case "price_asc":
          return "Price: Low to High";
        case "price_desc":
          return "Price: High to Low";
        case "newest":
          return "Newest First";
        case "oldest":
          return "Oldest First";
        default:
          return "Sorted";
      }
    };

    return (
      <motion.div
        key={filters.sort} // This key ensures animation plays when sort changes
        initial={{ opacity: 0, scale: 0.8, x: -10 }}
        animate={{ opacity: 1, scale: 1, x: 0 }}
        transition={{
          type: "spring",
          stiffness: 500,
          damping: 25,
        }}
        className="inline-flex items-center bg-rose-100 text-rose-800 px-3 py-1 rounded-full text-sm font-medium"
      >
        <span className="mr-1">{getSortIcon()}</span>
        {getSortLabel()}
      </motion.div>
    );
  };

  return (
    <div className="max-w-[90rem] mx-auto px-4 sm:px-6 lg:px-8 py-8">
      {/* Hero Section */}
      <motion.div
        initial={{ opacity: 0, y: -20 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.6 }}
        className="mb-12 text-center"
      >
        <h1 className="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl mb-4">
          <span className="block">Discover Your</span>
          <span className="block text-rose-500">Perfect Beauty</span>
        </h1>
        <p className="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
          Explore our curated collection of premium cosmetics for your self-care
          routine&apos;s essentials.
        </p>
      </motion.div>

      {/* Cart button - Using CartContext functions and cart count */}
      <div className="flex justify-end mb-6">
        <button
          onClick={openCart}
          className="flex items-center gap-2 bg-rose-500 hover:bg-rose-600 text-white px-4 py-2 rounded-full transition-all duration-200 shadow-md"
        >
          <ShoppingBag className="h-5 w-5" />
          <span>Cart ({getCartCount()})</span>
        </button>
      </div>

      {/* Mobile Filter Toggle */}
      <div className="lg:hidden mb-6">
        <button
          onClick={toggleFilters}
          className="flex items-center justify-center w-full px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200"
        >
          <span className="mr-2">
            {filterActive ? "Hide Filters" : "Show Filters"}
          </span>
          <svg
            className={`w-5 h-5 transform transition-transform duration-200 ${
              filterActive ? "rotate-180" : ""
            }`}
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              strokeWidth={2}
              d="M19 9l-7 7-7-7"
            />
          </svg>
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Sidebar - hidden on mobile unless toggled */}
        <motion.div
          className={`lg:col-span-3 ${
            filterActive ? "block" : "hidden lg:block"
          }`}
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.5 }}
        >
          <div className="bg-white p-6 rounded-lg shadow-md sticky top-24">
            <h2 className="text-xl font-semibold mb-6 text-gray-800">
              Filters
            </h2>
            <SideBar
              onFilterChange={handleFilterChange}
              activeFilters={filters}
            />
          </div>
        </motion.div>

        {/* Product Grid */}
        <div className="lg:col-span-9">
          {/* Showing filter and sort status */}
          <div className="mb-6 flex flex-col xs:flex-row items-start xs:items-center justify-between gap-3">
            <div className="flex items-center space-x-2">
              {filters.categories && filters.categories.length > 0 && (
                <span className="text-sm text-gray-600">
                  Showing results for:
                  <span className="font-medium ml-1">
                    {filters.categories.join(", ")}
                  </span>
                </span>
              )}

              {/* Sort indicator with animation */}
              {filters.sort && <SortIndicator />}
            </div>
            <div className="flex space-x-4">
              {filters.categories && filters.categories.length > 0 && (
                <button
                  onClick={() => handleFilterChange("categories", [])}
                  className="text-[12px] font-bold py-2 px-4  bg-rose-100 rounded-full text-rose-500 hover:text-rose-700 transition-colors"
                >
                  Clear Category Filter
                </button>
              )}
              {filters.sort && (
                <button
                  onClick={() => handleFilterChange("sort", "")}
                  className="text-[12px] font-bold py-2 px-4   bg-rose-100 rounded-full text-rose-500 hover:text-rose-700 transition-colors"
                >
                  Clear Sort
                </button>
              )}
            </div>
          </div>

          {/* Loading state */}
          {loading || isSearching ? (
            <div className="flex justify-center items-center h-64">
              <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-rose-500"></div>
            </div>
          ) : error ? (
            <div className="text-center py-12">
              <h3 className="text-lg font-medium text-gray-900">Error</h3>
              <p className="mt-2 text-sm text-gray-500">{error}</p>
            </div>
          ) : filteredProducts.length === 0 ? (
            <div className="text-center py-12">
              <h3 className="text-lg font-medium text-gray-900">
                No products found
              </h3>
              <p className="mt-2 text-sm text-gray-500">
                Try adjusting your filters to find what you&apos;re looking for.
              </p>
            </div>
          ) : (
            <motion.div
              className="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6"
              variants={containerVariants}
              initial="hidden"
              animate="visible"
              key={filters.sort} // This key change triggers re-render animation on sort change
              transition={{
                ...containerVariants.visible.transition,
                // Add special animation properties when sorting
                ...(isSorting && {
                  type: "spring",
                  stiffness: 200,
                  damping: 15,
                }),
              }}
            >
              {filteredProducts.map((product, index) => (
                <motion.div
                  key={`product-${product.product_id || index}`}
                  variants={itemVariants}
                  // Only apply layout animation when sorting
                  {...(isSorting && { layout: true })}
                  transition={{
                    ...itemVariants.visible.transition,
                    // Add special transitions for individual items when sorting
                    ...(isSorting && {
                      type: "spring",
                      stiffness: 300,
                      damping: 25,
                    }),
                  }}
                >
                  <ProductCard
                    product={product}
                    onAddToCart={handleAddToCart}
                    onToggleWishlist={handleToggleWishlist}
                    isInWishlist={wishlist.includes(product.product_id)}
                  />
                </motion.div>
              ))}
            </motion.div>
          )}
        </div>
      </div>

      {/* Toast Container */}
      <ToastContainer />
    </div>
  );
};

export default Shop;