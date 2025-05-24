import React, { useState } from "react";
import Image from "next/image";
import Link from "next/link";
import { Star, Heart, Eye, Clock, Flame, Zap } from "lucide-react";
import { TrendingProductCardProps } from "@/types/product"; // Adjust the import path as necessary

const TrendingProductCard: React.FC<TrendingProductCardProps> = ({
  product,
  onToggleWishlist,
  isInWishlist = false,
  salesCount = Math.floor(Math.random() * 1000) + 1,
}) => {
  const [isHovering, setIsHovering] = useState(false);

  // Create image array from product data
  const images = [
    `/assets/product/${product.image_path}`,
    ...(product.hover_image ? [`/assets/product/${product.hover_image}`] : []),
  ];
  const hasMultipleImages = images.length > 1;

  // Calculate discount using special promo or price comparison
  const discountPercent =
    product.special_promo === 1
      ? Math.round(
          ((product.price_2 - product.selling_price) / product.price_2) * 100
        )
      : Math.round(
          ((product.wholesale_price - product.selling_price) /
            product.wholesale_price) *
            100
        );

  const originalPrice =
    product.special_promo === 1 ? product.price_2 : product.wholesale_price;
  const currentPrice = product.selling_price;
  const rating = parseFloat(product.rating);

  return (
    <div
      className="group relative bg-white dark:bg-gray-900 rounded-3xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-500 border border-gray-100 dark:border-gray-800 hover:border-pink-200 dark:hover:border-pink-800 hover:-translate-y-2 my-8"
      onMouseEnter={() => setIsHovering(true)}
      onMouseLeave={() => setIsHovering(false)}
    >
      {/* Trending indicator with modern gradient */}
      <div className="absolute left-0 top-6 z-20">
        <div className="bg-gradient-to-r from-pink-500 via-purple-500 to-indigo-500 text-white py-1.5 px-4 rounded-r-2xl shadow-lg flex items-center gap-1.5 backdrop-blur-sm">
          <Zap className="h-4 w-4 animate-pulse" />
          <span className="font-semibold text-sm tracking-wide">TRENDING</span>
        </div>
      </div>

      {/* Image container with modern aspect ratio */}
      <div className="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
        {/* Main image */}
        <Image
          src={images[0]}
          alt={product.display_name}
          className={`w-full h-72 object-cover transform transition-all duration-700 ${
            isHovering && hasMultipleImages
              ? "opacity-0 scale-110"
              : "opacity-100 scale-100"
          } group-hover:scale-105`}
          width={1000}
          height={1000}
          priority
        />

        {/* Second image on hover */}
        {hasMultipleImages && (
          <Image
            src={images[1]}
            alt={`${product.display_name} - alternate view`}
            className={`absolute inset-0 w-full h-72 object-cover transform transition-all duration-700 ${
              isHovering ? "opacity-100 scale-105" : "opacity-0 scale-100"
            }`}
            width={1000}
            height={1000}
          />
        )}

        {/* Modern sales counter with glass effect */}
        <div className="absolute top-6 right-6 bg-white/20 backdrop-blur-md border border-white/30 text-white px-3 py-1.5 rounded-2xl text-sm font-medium shadow-lg flex items-center gap-1.5">
          <Flame className="h-4 w-4 text-orange-400" />
          <span className="text-white drop-shadow-sm">{salesCount} sold</span>
        </div>

        {/* Limited time offer with modern styling */}
        {product.special_promo === 1 && (
          <div className="absolute bottom-6 left-6 bg-black/60 backdrop-blur-md text-white px-4 py-2 rounded-2xl text-sm font-medium shadow-lg flex items-center gap-2 border border-white/20">
            <Clock className="h-4 w-4 text-yellow-400" />
            <span>{product.special_promo_message || "Limited time offer"}</span>
          </div>
        )}

        {/* Modern wishlist button */}
        {onToggleWishlist && (
          <button
            onClick={(e) => {
              e.stopPropagation();
              onToggleWishlist(product.product_id);
            }}
            className="absolute top-16 right-6 p-2.5 rounded-2xl bg-white/90 backdrop-blur-sm shadow-lg transition-all hover:bg-white hover:scale-110 hover:shadow-xl border border-white/50 group/heart"
            aria-label={
              isInWishlist ? "Remove from wishlist" : "Add to wishlist"
            }
          >
            <Heart
              className={`h-5 w-5 transition-all ${
                isInWishlist
                  ? "text-pink-500 fill-pink-500 scale-110"
                  : "text-gray-600 group-hover/heart:text-pink-500 group-hover/heart:scale-110"
              }`}
            />
          </button>
        )}

        {/* Modern image indicators */}
        {hasMultipleImages && (
          <div className="absolute bottom-6 right-6 flex gap-2">
            {images.slice(0, 2).map((_, index) => (
              <span
                key={index}
                className={`h-2 w-2 rounded-full transition-all duration-300 ${
                  (index === 0 && !isHovering) || (index === 1 && isHovering)
                    ? "bg-white scale-125 shadow-lg"
                    : "bg-white/40 hover:bg-white/60"
                }`}
              />
            ))}
          </div>
        )}

        {/* Gradient overlay for better text readability */}
        <div className="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent pointer-events-none" />
      </div>

      {/* Content area with modern spacing */}
      <div className="p-6 space-y-4">
        {/* Category badge with modern design */}
        <div className="flex items-center justify-between">
          <span className="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-pink-50 to-purple-50 dark:from-pink-950 dark:to-purple-950 text-pink-700 dark:text-pink-300 font-medium text-xs tracking-wide border border-pink-200 dark:border-pink-800">
            {product.category}
          </span>

          {/* Discount badge */}
          {discountPercent > 0 && (
            <span className="inline-flex items-center px-2.5 py-1 rounded-full bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold text-xs tracking-wide shadow-lg">
              -{discountPercent}%
            </span>
          )}
        </div>

        {/* Product name with modern typography */}
        <h3 className="font-bold text-lg text-gray-900 dark:text-white leading-tight line-clamp-2 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition-colors">
          {product.display_name}
        </h3>

        {/* Rating with modern stars */}
        <div className="flex items-center gap-3">
          <div className="flex items-center">
            {[...Array(5)].map((_, i) => (
              <Star
                key={i}
                className={`h-4 w-4 ${
                  i < Math.floor(rating)
                    ? "text-yellow-400 fill-yellow-400"
                    : "text-gray-300 dark:text-gray-600"
                }`}
              />
            ))}
          </div>
          <span className="text-sm text-gray-500 dark:text-gray-400 font-medium">
            {rating.toFixed(1)} ({product.review})
          </span>
        </div>

        {/* Description with single line display */}
        <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-1 leading-relaxed truncate">
          {product.product_description}
        </p>

        {/* Price section with modern layout */}
        <div className="flex items-end justify-between pt-2">
          <div className="space-y-1">
            <div className="flex items-baseline gap-2">
              <span className="text-2xl font-bold text-gray-900 dark:text-white">
                {new Intl.NumberFormat("en-LK", {
                  style: "currency",
                  currency: "LKR",
                }).format(currentPrice)}
              </span>
              {discountPercent > 0 && (
                <span className="text-sm text-gray-500 dark:text-gray-400 line-through font-medium">
                  {new Intl.NumberFormat("en-LK", {
                    style: "currency",
                    currency: "LKR",
                  }).format(originalPrice)}
                </span>
              )}
            </div>
          </div>

          {/* Modern action buttons */}
          <div className="flex gap-2">
            <Link href={`/products/${product.slug}`}>
              <button className="group/btn bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white px-4 py-2.5 rounded-2xl text-sm font-semibold transition-all hover:shadow-lg hover:scale-105 flex items-center gap-1.5">
                <Eye className="h-4 w-4 group-hover/btn:scale-110 transition-transform" />
                <span>View</span>
              </button>
            </Link>
          </div>
        </div>
      </div>

      {/* Modern hover effect overlay */}
      <div className="absolute inset-0 bg-gradient-to-r from-pink-500/0 via-purple-500/0 to-indigo-500/0 group-hover:from-pink-500/5 group-hover:via-purple-500/5 group-hover:to-indigo-500/5 rounded-3xl transition-all duration-500 pointer-events-none" />

      {/* Subtle border glow on hover */}
      <div className="absolute inset-0 rounded-3xl border-2 border-transparent group-hover:border-gradient-to-r group-hover:from-pink-300 group-hover:via-purple-300 group-hover:to-indigo-300 transition-all duration-500 pointer-events-none" />
    </div>
  );
};

export default TrendingProductCard;
