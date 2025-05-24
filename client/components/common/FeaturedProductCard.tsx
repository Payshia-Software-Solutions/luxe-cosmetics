import React, { useState } from "react";
import Link from "next/link";
import Image from "next/image";
import { Star, Heart, ShoppingBag } from "lucide-react";
import { ProductCardProps } from "@/types/product"; // Import the interfaces from your product.ts file
import BadgeContainer from "./BadgeContainer";

const FeaturedProductCard: React.FC<ProductCardProps> = ({
  product,
  onAddToCart,
  onToggleWishlist,
  isInWishlist,
}) => {
  const [isHovering, setIsHovering] = useState(false);

  // Parse specifications from JSON string
  const specificationsObj = JSON.parse(product.specifications || "{}");
  const skinType = specificationsObj.skin_type || "All Skin Types";

  // Parse benefits from string to array
  const benefitsArray = product.benefits ? product.benefits.split(",") : [];

  // Create image paths array - main image and hover image if available
  const images = [
    `assets/images/products/${product.image_path}`, // Assuming images are in this directory
  ];

  if (product.hover_image) {
    images.push(`assets/images/products/${product.hover_image}`);
  }

  // Helper function to determine brand from brand_id
  const getBrandName = (brandId: number) => {
    // This would ideally be a lookup to a brands table
    // For now using a simple switch case
    switch (brandId) {
      case 1:
        return "CeraVe";
      case 2:
        return "L'Oréal";
      case 3:
        return "Garnier";
      default:
        return "";
    }
  };

  // Check if the product has a special promo
  const hasPromo = product.special_promo > 0;

  const imageBasePath = "/assets/product/";

  return (
    <div
      className="group h-full"
      onMouseEnter={() => setIsHovering(true)}
      onMouseLeave={() => setIsHovering(false)}
    >
      <Link href={`/products/${product.slug}`} className="h-full block">
        {/* Updated card with fixed height for uniformity */}
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl flex flex-col ">
          {/* Fixed height image container */}
          <div className="relative w-full h-[270px] sm:h-[220px] md:h-[270px] flex-shrink-0">
            {/* Main image */}

            <div className="relative w-full h-full overflow-hidden rounded-t-lg group">
              <Image
                src={`${imageBasePath}${product.image_path}`}
                alt={product.product_name}
                width={500}
                height={500}
                className="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-300 opacity-100 group-hover:opacity-0"
              />
              {product.hover_image && (
                <Image
                  src={`${imageBasePath}${product.hover_image}`}
                  alt={`${product.product_name} hover`}
                  width={500}
                  height={500}
                  className="absolute top-0 left-0 w-full h-full object-cover transition-opacity duration-300 opacity-0 group-hover:opacity-100"
                />
              )}
            </div>

            {/* Category badge */}
            <div className="absolute top-2 left-2">
              <span className="px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase rounded bg-pink-100 text-pink-700 dark:bg-pink-900 dark:text-pink-200">
                {product.category}
              </span>
            </div>

            {/* Wishlist button */}
            <button
              onClick={(e) => {
                e.preventDefault();
                onToggleWishlist(product.product_id);
              }}
              className="absolute top-2 right-2 p-1.5 sm:p-2 rounded-full bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-sm transition-all hover:scale-110"
              aria-label={
                isInWishlist ? "Remove from wishlist" : "Add to wishlist"
              }
            >
              <Heart
                className={`h-4 w-4 sm:h-5 sm:w-5 ${
                  isInWishlist
                    ? "text-pink-600 fill-pink-600"
                    : "text-gray-600 dark:text-gray-300"
                }`}
              />
            </button>

            {/* Image indicator dots */}
            {images.length > 1 && (
              <div className="absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                {images.slice(0, 2).map((_, index) => (
                  <span
                    key={index}
                    className={`h-1.5 w-1.5 sm:h-2 sm:w-2 rounded-full ${
                      (index === 0 && !isHovering) ||
                      (index === 1 && isHovering)
                        ? "bg-white"
                        : "bg-white/50"
                    }`}
                  />
                ))}
              </div>
            )}

            {/* Special promo tag if applicable */}
            {hasPromo && (
              <div className="absolute bottom-2 left-2">
                <span className="px-2 py-0.5 text-[10px] sm:text-xs font-bold uppercase rounded bg-red-100 text-red-700">
                  {product.special_promo_type === "percentage"
                    ? `${product.special_promo}% OFF`
                    : `${product.special_promo} OFF`}
                </span>
              </div>
            )}
          </div>

          {/* Content area with fixed proportions */}
          <div className="p-3 sm:p-3 md:p-4 flex flex-col flex-grow min-h-0">
            {/* Brand area - fixed height */}
            <div className="h-6 sm:h-6 flex-shrink-0">
              <p className="text-sm sm:text-sm font-medium text-pink-600 dark:text-pink-400 uppercase truncate">
                {getBrandName(product.brand_id)}
              </p>
            </div>

            {/* Product name - fixed height with overflow handling */}
            <div className="mb-2 flex-shrink-0 border-b pb-2">
              <h3 className="text-base sm:text-lg md:text-lg font-bold text-gray-900 dark:text-white line-clamp-2 overflow-hidden min-h-[3rem]">
                {product.display_name || product.product_name}
              </h3>
            </div>

            {/* Rating - fixed height */}
            <div className="flex items-center mb-2 sm:mb-2 flex-shrink-0">
              <Star className="text-yellow-400 fill-current flex-shrink-0" />
              <span className="ml-1 text-sm sm:text-sm text-gray-600 dark:text-gray-300 truncate">
                {parseFloat(product.rating).toFixed(1)} ({product.review})
              </span>
            </div>

            {/* Badges - fixed height with overflow handling */}
            <BadgeContainer
              skinType={skinType}
              benefitsArray={benefitsArray}
              product={product}
            />

            {/* Price and button - pushed to bottom */}
            <div className=" pt-2 flex-shrink-0">
              <div className="flex items-center justify-between">
                <span className="text-lg sm:text-lg md:text-xl font-bold text-gray-900 dark:text-white">
                  LKR {product.selling_price.toFixed(2)}
                </span>

                <button
                  onClick={(e) => {
                    e.preventDefault();
                    onAddToCart(product.product_id);
                  }}
                  className="flex items-center justify-center gap-1 bg-pink-600 hover:bg-pink-700 text-white px-3 sm:px-3 md:px-4 py-2 sm:py-2 rounded-full text-sm sm:text-sm font-medium transition-colors whitespace-nowrap"
                >
                  <ShoppingBag className="h-4 w-4 sm:h-4 sm:w-4 flex-shrink-0" />
                  <span className="inline sm:inline">Add to Cart</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </Link>
    </div>
  );
};

export default FeaturedProductCard;
