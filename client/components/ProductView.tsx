"use client";
import React, { useState, useEffect } from "react";
import {
  Star,
  Heart,
  Share2,
  Minus,
  Plus,
  ShoppingCart,
  ChevronRight,
} from "lucide-react";
import { toast, ToastContainer } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import Link from "next/link";
import Image from "next/image";

import { CartItem } from "@/types/CartItem";
import { useCart } from "./CartContext";

import { FormattedProduct } from "@/types/ViewProduct"; // Adjust the import path as necessary

interface ProductViewProps {
  product: FormattedProduct;
}

// Fixed image path function to ensure consistent path structure
const getImagePath = (imagePath: string): string => {
  // Return the fallback image path if no image is provided
  if (!imagePath) {
    return "/assets/product/No_Image_Available.jpg";
  }

  try {
    // Check if the path already includes "/assets/product/" correctly
    if (
      imagePath.includes("/assets/product/") &&
      !imagePath.includes("/images/")
    ) {
      return imagePath;
    }

    // For paths that include "/images/" which seem to be causing problems
    if (imagePath.includes("/images/")) {
      return "/assets/product/No_Image_Available.jpg";
    }

    // Remove any leading slashes and get just the filename
    const filename = imagePath.replace(/^\/+/, "").split("/").pop();

    // If it's already a full URL, return it
    if (imagePath.startsWith("http://") || imagePath.startsWith("https://")) {
      return imagePath;
    }

    // Create the proper path with the correct structure
    return `/assets/product/${filename}`;
  } catch (error) {
    console.error("Error processing image path:", error);
    return "/assets/product/No_Image_Available.jpg";
  }
};

export default function ProductView({ product }: ProductViewProps) {
  const [quantity, setQuantity] = useState(1);
  const [selectedImage, setSelectedImage] = useState(0);
  const [activeTab, setActiveTab] = useState("description");
  const [imageError, setImageError] = useState<Record<number, boolean>>({});
  const [processedImages, setProcessedImages] = useState<string[]>([]);

  // Use the cart context
  const { addToCart, openCart, getCartCount } = useCart();

  useEffect(() => {
    // Reset state when product changes
    setSelectedImage(0);
    setImageError({});

    // Process the image paths when product changes
    if (product && product.images) {
      // Pre-process all images to remove problematic paths
      const processed = product.images.map((img) => {
        // Specifically check for problematic patterns like "/images/" in the path
        if (img && (img.includes("/images/") || img.includes("placeholder"))) {
          console.log(`Replacing problematic image path: ${img}`);
          return "/assets/product/No_Image_Available.jpg";
        }
        return getImagePath(img);
      });

      setProcessedImages(processed);
      console.log("Processed image paths:", processed);
    }
  }, [product]);

  // Handle image load error - completely replace with fallback image
  const handleImageError = (index: number) => {
    console.warn(`Image load failed at index ${index}. Using fallback image.`);

    // Update the processed images array with the fallback image path for this index
    setProcessedImages((current) => {
      const updated = [...current];
      updated[index] = "/assets/product/No_Image_Available.jpg";
      return updated;
    });

    // Also update the error state
    setImageError((prev) => ({ ...prev, [index]: true }));
  };

  const handleAddToCart = () => {
    // Create the cart item with the necessary structure
    const newCartItem: CartItem = {
      id: product.id,
      name: product.name,
      price: product.price,
      quantity: quantity,
      image:
        getImagePath(product.images[0]) ||
        "/assets/product/No_Image_Available.jpg", // Use processed image path with fallback
    };

    // Use the addToCart function from context
    addToCart(newCartItem);

    // Show success toast
    toast.success(`${product.name} added to cart!`);

    // Open the cart drawer/modal
    openCart();

    // Reset quantity
    setQuantity(1);
  };

  // We'll remove the relatedProducts functionality for now as it requires data we don't have
  // If needed, this can be passed as a prop or fetched separately

  return (
    <>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {/* Breadcrumbs */}
        <nav className="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-8">
          {product.breadcrumbs.map((crumb, index) => (
            <React.Fragment key={index}>
              {index > 0 && <ChevronRight className="h-4 w-4" />}
              {index === product.breadcrumbs.length - 1 ? (
                <span className="text-pink-600 font-medium">{crumb}</span>
              ) : (
                <Link
                  href={
                    index === 0
                      ? "/"
                      : index === 1
                      ? "/shop"
                      : `/category/${product.category.toLowerCase()}`
                  }
                  className="hover:text-pink-600"
                >
                  {crumb}
                </Link>
              )}
            </React.Fragment>
          ))}
        </nav>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
          {/* Product Images */}
          <div className="space-y-4">
            <div className="aspect-w-1 aspect-h-1 rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800">
              {imageError[selectedImage] ? (
                <div className="w-full h-96 flex items-center justify-center text-gray-500">
                  <p>Image not found</p>
                </div>
              ) : (
                <Image
                  src={
                    processedImages[selectedImage] ||
                    "/assets/product/No_Image_Available.jpg"
                  }
                  alt={product.name}
                  className="w-full h-96 object-cover"
                  width={1000}
                  height={1000}
                  onError={() => handleImageError(selectedImage)}
                  priority
                />
              )}
            </div>
            {processedImages.length > 1 && (
              <div className="grid grid-cols-4 gap-4">
                {processedImages.map((image, index) => (
                  <button
                    key={index}
                    onClick={() => setSelectedImage(index)}
                    className={`rounded-lg overflow-hidden border-2 ${
                      selectedImage === index
                        ? "border-pink-600"
                        : "border-transparent"
                    } bg-gray-100 dark:bg-gray-800`}
                  >
                    <Image
                      src={image}
                      alt={`${product.name} thumbnail ${index + 1}`}
                      className="w-full h-24 object-cover"
                      width={200}
                      height={200}
                      onError={() => handleImageError(index)}
                    />
                  </button>
                ))}
              </div>
            )}
          </div>

          {/* Product Info */}
          <div className="space-y-6">
            <span className="inline-block bg-pink-100 dark:bg-pink-900 text-pink-600 dark:text-pink-300 px-3 py-1 rounded-full text-sm font-medium">
              {product.category}
            </span>

            <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
              {product.name}
            </h1>

            <div className="flex items-center space-x-4">
              <div className="flex items-center">
                <Star className="h-5 w-5 text-yellow-400 fill-current" />
                <span className="ml-1 text-sm text-gray-600 dark:text-gray-300">
                  {product.rating} ({product.reviews.length} reviews)
                </span>
              </div>
              <button
                className="text-gray-400 hover:text-pink-600 transition-colors"
                aria-label="Add to wishlist"
              >
                <Heart className="h-6 w-6" />
              </button>
              <button
                className="text-gray-400 hover:text-pink-600 transition-colors"
                aria-label="Share product"
              >
                <Share2 className="h-6 w-6" />
              </button>
            </div>

            <div className="text-3xl font-bold text-gray-900 dark:text-white">
              ${product.price.toFixed(2)}
            </div>

            <p className="text-gray-600 dark:text-gray-300">
              {product.description}
            </p>

            {product.benefits.length > 0 && (
              <div className="space-y-4">
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                  Key Benefits
                </h3>
                <ul className="list-disc list-inside space-y-2 text-gray-600 dark:text-gray-300">
                  {product.benefits.map((benefit, index) => (
                    <li key={index}>{benefit}</li>
                  ))}
                </ul>
              </div>
            )}

            <div className="pt-6">
              <div className="flex items-center space-x-6">
                <div className="flex items-center border border-gray-300 rounded-full">
                  <button
                    onClick={() => setQuantity(Math.max(1, quantity - 1))}
                    className="p-2 hover:text-pink-600 transition-colors"
                    aria-label="Decrease quantity"
                  >
                    <Minus className="h-5 w-5" />
                  </button>
                  <span
                    className="w-12 text-center"
                    aria-live="polite"
                    aria-label={`Quantity: ${quantity}`}
                  >
                    {quantity}
                  </span>
                  <button
                    onClick={() => setQuantity(quantity + 1)}
                    className="p-2 hover:text-pink-600 transition-colors"
                    aria-label="Increase quantity"
                  >
                    <Plus className="h-5 w-5" />
                  </button>
                </div>
                <button
                  onClick={handleAddToCart}
                  className="flex-1 bg-pink-600 hover:bg-pink-700 text-white px-8 py-3 rounded-full font-medium flex items-center justify-center space-x-2 transition-colors"
                >
                  <ShoppingCart className="h-5 w-5" />
                  <span>Add to Cart</span>
                </button>
              </div>

              {/* Cart count indicator */}
              <div className="mt-4 text-sm text-gray-600 dark:text-gray-300">
                Cart: {getCartCount()} item(s)
              </div>
            </div>
          </div>
        </div>

        {/* Product Details Tabs - FIXED FOR ACCESSIBILITY */}
        <div className="border-b border-gray-200 dark:border-gray-700 mb-8">
          <div className="flex space-x-8" role="tablist" aria-label="Product information tabs">
            {["description", "specifications", "reviews"].map((tab) => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                role="tab"
                aria-selected={activeTab === tab}
                aria-controls={`${tab}-panel`}
                id={`${tab}-tab`}
                tabIndex={activeTab === tab ? 0 : -1}
                className={`py-4 px-1 border-b-2 font-medium text-sm ${
                  activeTab === tab
                    ? "border-pink-600 text-pink-600"
                    : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"
                }`}
              >
                {tab.charAt(0).toUpperCase() + tab.slice(1)}
              </button>
            ))}
          </div>
        </div>

        {/* Tab Content */}
        <div className="mb-16">
          {activeTab === "description" && (
            <div
              id="description-panel"
              role="tabpanel"
              aria-labelledby="description-tab"
              className="prose dark:prose-invert max-w-none"
            >
              <p className="whitespace-pre-line">{product.longDescription}</p>
            </div>
          )}

          {activeTab === "specifications" && (
            <div
              id="specifications-panel"
              role="tabpanel"
              aria-labelledby="specifications-tab"
              className="grid grid-cols-1 md:grid-cols-2 gap-8"
            >
              {Object.entries(product.specifications).map(([key, value]) => (
                <div key={key} className="border-b dark:border-gray-700 pb-4">
                  <dt className="text-sm font-medium text-gray-500 dark:text-gray-400">
                    {key}
                  </dt>
                  <dd className="mt-1 text-sm text-gray-900 dark:text-white">
                    {Array.isArray(value) ? value.join(", ") : value}
                  </dd>
                </div>
              ))}
            </div>
          )}

          {activeTab === "reviews" && (
            <div
              id="reviews-panel"
              role="tabpanel"
              aria-labelledby="reviews-tab"
              className="space-y-8"
            >
              {product.reviews.length > 0 ? (
                product.reviews.map((review) => (
                  <div
                    key={review.id}
                    className="border-b dark:border-gray-700 pb-8"
                  >
                    <div className="flex items-center justify-between mb-4">
                      <div>
                        <div className="flex items-center">
                          <div
                            className="flex items-center"
                            aria-label={`Rated ${review.rating} out of 5 stars`}
                          >
                            {[...Array(5)].map((_, i) => (
                              <Star
                                key={i}
                                className={`h-5 w-5 ${
                                  i < review.rating
                                    ? "text-yellow-400 fill-current"
                                    : "text-gray-300"
                                }`}
                              />
                            ))}
                          </div>
                          <h4 className="ml-2 text-sm font-bold text-gray-900 dark:text-white">
                            {review.title}
                          </h4>
                        </div>
                        <p className="mt-1 text-sm text-gray-500">
                          By {review.user} on {review.date}
                        </p>
                      </div>
                      {review.verified && (
                        <span className="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                          Verified Purchase
                        </span>
                      )}
                    </div>
                    <p className="text-gray-600 dark:text-gray-300">
                      {review.comment}
                    </p>
                    <div className="mt-4 flex items-center space-x-4">
                      <button className="text-sm text-gray-500 hover:text-gray-700">
                        Helpful ({review.helpful})
                      </button>
                      <button className="text-sm text-gray-500 hover:text-gray-700">
                        Report
                      </button>
                    </div>
                  </div>
                ))
              ) : (
                <p className="text-gray-600 dark:text-gray-300">
                  No reviews yet. Be the first to review this product!
                </p>
              )}
            </div>
          )}
        </div>
      </div>

      {/* Related Products section removed for now */}

      {/* Toast Container */}
      <ToastContainer />
    </>
  );
}