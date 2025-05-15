"use client";

import React, { useState, useEffect } from "react";
import ProductCard from "../common/ProductCard";
import SideBar from "./SideBar";
import { motion } from "framer-motion";
import axios from "axios";

interface Product {
  id: number;
   slug: string;
   name: string;
   price: number;
   rating: number;
   review: number;
   description: string;
   longDescription: string;
   benefits: string[];
   specifications: Record<string, string>;
   ingredients: string;
   images: string[];
   category: string;
   breadcrumbs: string[];
   metaDescription: string;
   reviews: Review[];
}

export interface Review {
 id: number;
 user: string;
 rating: number;
 date: string;
 title: string;
 comment: string;
 verified: boolean;
 helpful: number;
}


const Shop: React.FC = () => {
  const [wishlist, setWishlist] = useState<number[]>([]);
  const [filterActive, setFilterActive] = useState(false);
  const [filteredProducts, setFilteredProducts] = useState<Product[]>([]);
    const [products, setProducts] = useState<Product[]>([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    // Fetch products using axios
    useEffect(() => {
      const fetchProducts = async () => {
        try {
          setLoading(true);
          const response = await axios.get(
            `${process.env.NEXT_PUBLIC_API_URL}/products`
          );
  
          console.log("API Response:", response.data);
  
          // Process and validate the products data
          let productsData = response.data;
  
          // Handle different response formats
          if (!Array.isArray(productsData)) {
            if (productsData?.products) {
              productsData = productsData.products;
            } else if (productsData?.data) {
              productsData = productsData.data;
            } else if (productsData?.results) {
              productsData = productsData.results;
            } else if (productsData?.product_id) {
              // Single product object
              productsData = [productsData];
            } else {
              throw new Error("Unexpected response format");
            }
          }
          
          // Validate we have an array
          if (!Array.isArray(productsData)) {
            throw new Error("Could not extract products array from response");
          }
  
          // Map API field names to expected Product interface field names
          const validProducts: Product[] = productsData
            .filter((product) => {
              // Basic validation - ensure it has minimum required properties
              return (
                product &&
                typeof product === "object" &&
                (product.id || product.product_id) &&
                (product.name || product.product_name || product.display_name)
              );
            })
            .map((product) => {
              // Process images
              const images = [];
              console.log(
                `Processing images for product ${
                  product.id || product.product_id
                }:`,
                product
              );
  
              // Check for image_path (your actual database column name)
              if (product.image_path) {
                const imageUrl = product.image_path.startsWith("/")
                  ? product.image_path
                  : `/assets/product/${product.image_path}`;
                console.log("Using image_path with path:", imageUrl);
                images.push(imageUrl);
              }
              // Still keep these checks as fallbacks
              else if (product.image_url) {
                const imageUrl = product.image_url.startsWith("/")
                  ? product.image_url
                  : `/assets/product/${product.image_url}`;
                console.log("Using image_url with path:", imageUrl);
                images.push(imageUrl);
              } else if (
                product.images &&
                Array.isArray(product.images) &&
                product.images.length > 0
              ) {
                const processedImages = product.images.map((img: string) => {
                  const fullPath = img.startsWith("/")
                    ? img
                    : `/assets/product/${img}`;
                  return fullPath;
                });
                console.log("Final processed images array:", processedImages);
                images.push(...processedImages);
              } else {
                // Add placeholder image if no images are available
                console.log("No images found, using placeholder");
                images.push("/placeholder-product.jpg");
              }
  
              // If there's only one image, duplicate it to enable hover effect
              if (images.length === 1) {
                console.log("Only one image found, duplicating for hover effect");
                images.push(images[0]);
              }
  
              console.log("Final images array for product:", images);
  
              // Ensure all required properties exist with defaults if needed
              return {
                id: product.id || product.product_id?.toString(),
                slug:
                  product.slug ||
                  (product.product_name || product.name || "")
                    .toLowerCase()
                    .replace(/\s+/g, "-"),
                name:
                  product.name || product.product_name || product.display_name,
                price: Number(product.price || product.selling_price || 0),
                rating: Number(product.rating || product.average_rating || 5),
                review: Number(
                  product.review_count || product.reviews_count || 0
                ),
                description:
                  product.description || product.short_description || "",
                longDescription:
                  product.long_description || product.description || "",
                benefits: Array.isArray(product.benefits) ? product.benefits : [],
                specifications: product.specifications || {},
                ingredients: product.ingredients || "",
                images: images,
                category: product.category || "Beauty",
                breadcrumbs: Array.isArray(product.breadcrumbs)
                  ? product.breadcrumbs
                  : [],
                metaDescription: product.meta_description || "",
                reviews: Array.isArray(product.reviews) ? product.reviews : [],
              };
            });
  
          console.log("Processed Products:", validProducts);
          setProducts(validProducts);
          setFilteredProducts(validProducts);
          setError(null);
        } catch (err) {
          console.error("Error fetching products:", err);
          setError("Failed to load products. Please try again later.");
          // Initialize empty array to prevent undefined errors
          setProducts([]);
        } finally {
          setLoading(false);
        }
      };
  
      fetchProducts();
    }, []);

 

  const handleAddToCart = (productId:number) => {
    console.log(`Add to cart: ${productId}`);
  };

  const handleToggleWishlist = (productId:number) => {
    setWishlist((prev) =>
      prev.includes(productId)
        ? prev.filter((id) => id !== productId)
        : [...prev, productId]
    );
  };

  const toggleFilters = () => {
    setFilterActive(!filterActive);
  };

  // Staggered animation for product cards
  const containerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.1
      }
    }
  };

  const itemVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.1 }
    }
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
          Explore our curated collection of premium cosmetics for your self-care routine&apos;s essentials.
        </p>
      </motion.div>

      {/* Mobile Filter Toggle */}
      <div className="lg:hidden mb-6">
        <button
          onClick={toggleFilters}
          className="flex items-center justify-center w-full px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200"
        >
          <span className="mr-2">{filterActive ? "Hide Filters" : "Show Filters"}</span>
          <svg className={`w-5 h-5 transform transition-transform duration-200 ${filterActive ? 'rotate-180' : ''}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
      
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Sidebar - hidden on mobile unless toggled */}
        <motion.div 
          className={`lg:col-span-3 ${filterActive ? 'block' : 'hidden lg:block'}`}
          initial={{ opacity: 0, x: -20 }}
          animate={{ opacity: 1, x: 0 }}
          transition={{ duration: 0.5 }}
        >
          <div className="bg-white p-6 rounded-lg shadow-md sticky top-24">
            <h2 className="text-xl font-semibold mb-6 text-gray-800">Filters</h2>
            <SideBar />
          </div>
        </motion.div>

        {/* Product Grid */}
        <div className="lg:col-span-9">
          {filteredProducts.length === 0 ? (
            <div className="text-center py-12">
              <h3 className="text-lg font-medium text-gray-900">No products found</h3>
              <p className="mt-2 text-sm text-gray-500">Try adjusting your filter to find what you&apos;re looking for.</p>
            </div>
          ) : (
            <motion.div 
              className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
              variants={containerVariants}
              initial="hidden"
              animate="visible"
            >
              {filteredProducts.map((product) => (
                <motion.div key={product.id} variants={itemVariants}>
                  <ProductCard
                    product={product}
                    onAddToCart={handleAddToCart}
                    onToggleWishlist={handleToggleWishlist}
                    isInWishlist={wishlist.includes(product.id)}
                  />
                </motion.div>
              ))}
            </motion.div>
          )}
        </div>
      </div>
    </div>
  );
};

export default Shop;
