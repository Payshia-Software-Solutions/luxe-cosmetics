"use client";
import React, { useState } from "react";
import { FaStar } from "react-icons/fa";
import {
  GiLipstick,
  GiFaceToFace,
  GiEyeTarget,
  GiFragrance,
} from "react-icons/gi";
import { FaRegHeart } from "react-icons/fa";

// Import our components
import PriceFilter from "./PriceFilter";
import SectionHeader from "./SectionHeader";
import Checkbox from "./Checkbox";
import ActiveFilters from "./ActiveFilters";
import SortDropdown from "./SortDropdown";
// Import types
import { Category, SideBarProps } from "@/types/Sidebar";

const MobSideBar: React.FC<SideBarProps> = ({
  onFilterChange,
  activeFilters,
}) => {
  const [expandedSections, setExpandedSections] = useState({
    categories: true,
    priceRange: true,
    brands: true,
    rating: true,
    sale: true,
    sort: true,
  });

  const categories: Category[] = [
    { icon: <GiLipstick size={20} />, label: "Lips", count: 127 },
    { icon: <GiFaceToFace size={20} />, label: "Face", count: 84 },
    { icon: <GiEyeTarget size={20} />, label: "Eyes", count: 93 },
    { icon: <GiFragrance size={20} />, label: "Fragrance", count: 45 },
    { icon: <FaRegHeart size={20} />, label: "Skincare", count: 216 },
  ];

  const brands = [
    "Fenty Beauty",
    "Glossier",
    "Charlotte Tilbury",
    "Rare Beauty",
    "Dior",
  ];

  const toggleSection = (section: keyof typeof expandedSections) => {
    setExpandedSections({
      ...expandedSections,
      [section]: !expandedSections[section],
    });
  };

  const handleCategoryChange = (category: string) => {
    const currentCategories = activeFilters.categories || [];
    const newCategories = currentCategories.includes(category)
      ? currentCategories.filter((c: string) => c !== category)
      : [...currentCategories, category];

    onFilterChange("categories", newCategories);
  };

  const handleBrandChange = (brand: string) => {
    const currentBrands = activeFilters.brands || [];
    const newBrands = currentBrands.includes(brand)
      ? currentBrands.filter((b: string) => b !== brand)
      : [...currentBrands, brand];

    onFilterChange("brands", newBrands);
  };

  const handleRatingChange = (rating: number) => {
    const currentRatings = activeFilters.ratings || [];
    const newRatings = currentRatings.includes(rating)
      ? currentRatings.filter((r: number) => r !== rating)
      : [...currentRatings, rating];

    onFilterChange("ratings", newRatings);
  };

  const handleSaleChange = () => {
    onFilterChange("onSale", !activeFilters.onSale);
  };

  const handlePriceChange = (value: [number, number]) => {
    onFilterChange("priceRange", value);
  };

  const handleSortChange = (sortValue: string) => {
    onFilterChange("sort", sortValue);
  };

  const handleRemoveFilter = (type: string, value?: string | number) => {
    if (type === "all") {
      onFilterChange("resetAll", null);
      return;
    }

    if (type === "priceRange") {
      onFilterChange("priceRange", undefined);
      return;
    }

    if (type === "onSale") {
      onFilterChange("onSale", false);
      return;
    }

    if (type === "sort") {
      onFilterChange("sort", undefined);
      return;
    }

    if (type === "category" && value) {
      const newCategories = (activeFilters.categories || []).filter(
        (c: string | number) => c !== value
      );
      onFilterChange("categories", newCategories);
      return;
    }

    if (type === "brand" && value) {
      const newBrands = (activeFilters.brands || []).filter(
        (b: string | number) => b !== value
      );
      onFilterChange("brands", newBrands);
      return;
    }

    if (type === "rating" && value) {
      const newRatings = (activeFilters.ratings || []).filter(
        (r: string | number) => r !== value
      );
      onFilterChange("ratings", newRatings);
      return;
    }
  };

  return (
    <div className="bg-white dark:bg-[#161313]">
      <div className="space-y-6">
        {/* Sort Section */}
        <div>
          <SectionHeader
            title="Sort Products"
            isExpanded={expandedSections.sort}
            onToggle={() => toggleSection("sort")}
          />
          {expandedSections.sort && (
            <div className="mt-4">
              <SortDropdown
                onChange={handleSortChange}
                currentSort={activeFilters.sort || ""}
              />
            </div>
          )}
        </div>

        {/* Categories Section */}
        <div>
          <SectionHeader
            title="Categories"
            isExpanded={expandedSections.categories}
            onToggle={() => toggleSection("categories")}
          />
          {expandedSections.categories && (
            <div className="space-y-1 mt-2">
              {categories.map((category) => (
                <Checkbox
                  key={category.label}
                  id={`category-${category.label}`}
                  label={
                    <div className="flex items-center gap-2 text-gray-400  dark:text-gray-50">
                      <span className="text-gray-500">{category.icon}</span>
                      <span>{category.label}</span>
                      <span className="text-xs text-gray-400  dark:text-gray-50">
                        ({category.count})
                      </span>
                    </div>
                  }
                  checked={(activeFilters.categories || []).includes(
                    category.label
                  )}
                  onChange={() => handleCategoryChange(category.label)}
                />
              ))}
            </div>
          )}
        </div>

        {/* Price Range Section */}
        <div>
          <SectionHeader
            title="Price Range"
            isExpanded={expandedSections.priceRange}
            onToggle={() => toggleSection("priceRange")}
          />
          {expandedSections.priceRange && (
            <PriceFilter
              min={0}
              max={300}
              value={activeFilters.priceRange || [30, 200]}
              onChange={handlePriceChange}
            />
          )}
        </div>

        {/* Brands Section */}
        <div>
          <SectionHeader
            title="Brands"
            isExpanded={expandedSections.brands}
            onToggle={() => toggleSection("brands")}
          />
          {expandedSections.brands && (
            <div className="space-y-1 mt-2">
              {brands.map((brand) => (
                <Checkbox
                  key={brand}
                  id={`brand-${brand}`}
                  label={brand}
                  checked={(activeFilters.brands || []).includes(brand)}
                  onChange={() => handleBrandChange(brand)}
                />
              ))}
            </div>
          )}
        </div>

        {/* Rating Section */}
        <div>
          <SectionHeader
            title="Rating"
            isExpanded={expandedSections.rating}
            onToggle={() => toggleSection("rating")}
          />
          {expandedSections.rating && (
            <div className="space-y-1 mt-2">
              {[5, 4, 3, 2, 1].map((rating) => (
                <Checkbox
                  key={rating}
                  id={`rating-${rating}`}
                  label={
                    <div className="flex items-center">
                      <span>Rated {rating}+</span>
                      <div className="ml-2 flex">
                        {Array.from({ length: 5 }).map((_, i) => (
                          <FaStar
                            key={i}
                            size={12}
                            className={
                              i < rating ? "text-amber-400" : "text-gray-300"
                            }
                          />
                        ))}
                      </div>
                    </div>
                  }
                  checked={(activeFilters.ratings || []).includes(rating)}
                  onChange={() => handleRatingChange(rating)}
                />
              ))}
            </div>
          )}
        </div>

        {/* Sale Section */}
        <div>
          <SectionHeader
            title="Sale"
            isExpanded={expandedSections.sale}
            onToggle={() => toggleSection("sale")}
          />
          {expandedSections.sale && (
            <div className="mt-2">
              <Checkbox
                id="sale"
                label="On Sale"
                checked={activeFilters.onSale}
                onChange={handleSaleChange}
              />
            </div>
          )}
        </div>

        {/* Active Filters */}
        <ActiveFilters
          activeFilters={activeFilters}
          onRemoveFilter={handleRemoveFilter}
        />
      </div>
    </div>
  );
};

export default MobSideBar;
