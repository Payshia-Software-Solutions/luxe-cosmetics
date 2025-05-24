import React from "react";
import { ActiveFiltersProps } from "@/types/ActiveFiltersProps";

const ActiveFilters: React.FC<ActiveFiltersProps> = ({
  activeFilters,
  onRemoveFilter,
}) => {
  if (
    !activeFilters.categories?.length &&
    !activeFilters.brands?.length &&
    !activeFilters.ratings?.length &&
    !activeFilters.onSale &&
    !activeFilters.priceRange &&
    !activeFilters.sort
  ) {
    return null;
  }

  // Map sort values to readable labels
  const sortLabels: Record<string, string> = {
    price_asc: "Price: Low to High",
    price_desc: "Price: High to Low",
    newest: "Newest First",
    oldest: "Oldest First",
  };

  return (
    <div className="mb-6 ">
      <div className="flex items-center justify-between mb-3">
        <h3 className="text-sm font-medium text-gray-700">Active Filters</h3>
        <button
          onClick={() => onRemoveFilter("all")}
          className="text-xs text-rose-500 hover:text-rose-700 transition-colors"
        >
          Clear All
        </button>
      </div>
      <div className="flex flex-wrap gap-2">
        {activeFilters.priceRange && (
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group">
            ${activeFilters.priceRange[0]} - ${activeFilters.priceRange[1]}
            <button
              onClick={() => onRemoveFilter("priceRange")}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        )}

        {activeFilters.onSale && (
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group">
            On Sale
            <button
              onClick={() => onRemoveFilter("onSale")}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        )}

        {activeFilters.categories?.map((category) => (
          <span
            key={category}
            className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group"
          >
            {category}
            <button
              onClick={() => onRemoveFilter("category", category)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        ))}

        {activeFilters.brands?.map((brand) => (
          <span
            key={brand}
            className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group"
          >
            {brand}
            <button
              onClick={() => onRemoveFilter("brand", brand)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        ))}

        {activeFilters.ratings?.map((rating) => (
          <span
            key={rating}
            className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group"
          >
            {rating}+ Stars
            <button
              onClick={() => onRemoveFilter("rating", rating)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        ))}

        {activeFilters.sort && (
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600 group">
            {sortLabels[activeFilters.sort]}
            <button
              onClick={() => onRemoveFilter("sort")}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              ×
            </button>
          </span>
        )}
      </div>
    </div>
  );
};

export default ActiveFilters;
