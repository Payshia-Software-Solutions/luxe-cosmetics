"use client";

import React, { useState, useEffect } from "react";
import axios from "axios";
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation, Pagination, Autoplay } from "swiper/modules";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";

import ProductCard from "./common/ProductCard";

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


export default function FeaturedProducts() {
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [wishlist, setWishlist] = useState<number[]>([]);

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
            className="py-12 mb-12"
          >
            {products.map((product) => (
              <SwiperSlide key={product.id}>
                <ProductCard                  
                  product={product}
                  onAddToCart={handleAddToCart}
                  onToggleWishlist={handleToggleWishlist}
                  isInWishlist={wishlist.includes(product.id)}
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
    </section>
  );
}