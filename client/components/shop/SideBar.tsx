"use client";
import React, { useState, useEffect } from "react";
import { FaBars, FaRegHeart, FaStar, FaTags, FaFilter, FaChevronDown, FaChevronUp } from "react-icons/fa";
import { GiLipstick, GiFaceToFace, GiEyeTarget, GiFragrance } from "react-icons/gi";
import { Italiana, Julius_Sans_One } from "next/font/google";

// Define custom font styles
const italiana = Italiana({
  weight: "400",
  subsets: ["latin"],
});

const juliusSansOne = Julius_Sans_One({
  weight: "400",
  subsets: ["latin"],
});

interface CategoryProps {
  icon: React.ReactNode;
  label: string;
  count?: number;
}

const Category: React.FC<CategoryProps> = ({ icon, label, count }) => {
  return (
    <li className="my-3 transition-all duration-300 hover:translate-x-1">
      <a href="#" className="flex items-center justify-between group">
        <div className="flex items-center gap-3 text-lg">
          <span className="text-rose-400 group-hover:text-rose-600 transition-colors">{icon}</span>
          <span className="group-hover:text-rose-500 transition-colors">{label}</span>
        </div>
        {count && <span className="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">{count}</span>}
      </a>
    </li>
  );
};

interface PriceFilterProps {
  min: number;
  max: number;
  value: [number, number];
  onChange: (value: [number, number]) => void;
}

const PriceFilter: React.FC<PriceFilterProps> = ({ min, max, value, onChange }) => {
  const handleMinChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newMin = parseInt(e.target.value);
    onChange([Math.min(newMin, value[1] - 10), value[1]]);
  };

  const handleMaxChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newMax = parseInt(e.target.value);
    onChange([value[0], Math.max(newMax, value[0] + 10)]);
  };

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center mb-1">
        <div className="px-3 py-1.5 bg-gray-50 rounded-md border border-gray-200 w-24 text-center">
          <span className="text-sm font-medium">${value[0]}</span>
        </div>
        <div className="text-gray-400 text-xs">to</div>
        <div className="px-3 py-1.5 bg-gray-50 rounded-md border border-gray-200 w-24 text-center">
          <span className="text-sm font-medium">${value[1]}</span>
        </div>
      </div>
      <div className="relative h-2 bg-gray-200 rounded-full my-6">
        <div 
          className="absolute h-2 bg-gradient-to-r from-rose-300 to-rose-500 rounded-full"
          style={{
            left: `${((value[0] - min) / (max - min)) * 100}%`,
            right: `${100 - ((value[1] - min) / (max - min)) * 100}%`,
          }}
        ></div>
        <div 
          className="absolute h-5 w-5 bg-white border-2 border-rose-500 rounded-full -mt-1.5 shadow-md cursor-pointer hover:scale-110 transition-transform"
          style={{ left: `calc(${((value[0] - min) / (max - min)) * 100}% - 10px)` }}
        ></div>
        <div 
          className="absolute h-5 w-5 bg-white border-2 border-rose-500 rounded-full -mt-1.5 shadow-md cursor-pointer hover:scale-110 transition-transform"
          style={{ left: `calc(${((value[1] - min) / (max - min)) * 100}% - 10px)` }}
        ></div>
      </div>
      <div className="flex gap-4 relative">
        <input 
          type="range" 
          min={min} 
          max={max} 
          value={value[0]} 
          onChange={handleMinChange}
          className="w-full absolute opacity-0 cursor-pointer z-10 h-2"
        />
        <input 
          type="range" 
          min={min} 
          max={max} 
          value={value[1]} 
          onChange={handleMaxChange}
          className="w-full absolute opacity-0 cursor-pointer z-10 h-2"
        />
      </div>
    </div>
  );
};

interface SectionHeaderProps {
  title: string;
  isExpanded: boolean;
  toggleExpanded: () => void;
  count?: number;
}

const SectionHeader: React.FC<SectionHeaderProps> = ({ 
  title, 
  isExpanded, 
  toggleExpanded,
  count
}) => {
  return (
    <button 
      className="flex justify-between items-center w-full py-3 group transition-colors"
      onClick={toggleExpanded}
    >
      <div className="flex items-center gap-2">
        <div className={italiana.className}>
          <h3 className="text-xl font-bold group-hover:text-rose-500 transition-colors">{title}</h3>
        </div>
        {count !== undefined && (
          <span className="text-xs bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full">
            {count}
          </span>
        )}
      </div>
      <span className="w-6 h-6 flex items-center justify-center rounded-full text-gray-500 bg-gray-100 group-hover:bg-rose-100 group-hover:text-rose-500 transition-all">
        {isExpanded ? <FaChevronUp size={12} /> : <FaChevronDown size={12} />}
      </span>
    </button>
  );
};

interface CheckboxProps {
  id: string;
  label: React.ReactNode;
  checked?: boolean;
  onChange?: (e: React.ChangeEvent<HTMLInputElement>) => void;
}

const Checkbox: React.FC<CheckboxProps> = ({ id, label, checked = false, onChange }) => {
  return (
    <div className="flex items-center py-2">
      <div className="relative flex items-center">
        <input
          type="checkbox"
          id={id}
          checked={checked}
          onChange={onChange}
          className="opacity-0 absolute h-5 w-5 cursor-pointer"
        />
        <div className={`border-2 rounded w-5 h-5 flex flex-shrink-0 justify-center items-center mr-2 
          ${checked ? 'bg-rose-500 border-rose-500' : 'border-gray-300 bg-white'}`}
        >
          <svg 
            className={`fill-current w-3 h-3 text-white pointer-events-none ${checked ? 'opacity-100' : 'opacity-0'}`} 
            viewBox="0 0 20 20"
          >
            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
          </svg>
        </div>
      </div>
      <label
        htmlFor={id}
        className="ml-2 cursor-pointer text-gray-700 text-sm hover:text-rose-500 transition-colors select-none"
      >
        {label}
      </label>
    </div>
  );
};

interface ActiveFiltersProps {
  activeFilters: {
    priceRange?: [number, number];
    categories?: string[];
    brands?: string[];
    ratings?: number[];
    onSale?: boolean;
  };
  onClearFilter: (filterType: string, value?: any) => void;
}

const ActiveFilters: React.FC<ActiveFiltersProps> = ({ activeFilters, onClearFilter }) => {
  // Count total active filters
  const totalActiveFilters = 
    (activeFilters.categories?.length || 0) +
    (activeFilters.brands?.length || 0) +
    (activeFilters.ratings?.length || 0) +
    (activeFilters.onSale ? 1 : 0) +
    (activeFilters.priceRange ? 1 : 0);

  if (totalActiveFilters === 0) return null;

  return (
    <div className="mb-6">
      <div className="flex items-center justify-between mb-3">
        <h3 className="text-sm font-medium text-gray-700">Active Filters</h3>
        <button 
          onClick={() => onClearFilter('all')}
          className="text-xs text-rose-500 hover:text-rose-600 transition-colors"
        >
          Clear All
        </button>
      </div>
      <div className="flex flex-wrap gap-2">
        {activeFilters.priceRange && (
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600">
            ${activeFilters.priceRange[0]} - ${activeFilters.priceRange[1]}
            <button 
              onClick={() => onClearFilter('priceRange')}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              &times;
            </button>
          </span>
        )}
        
        {activeFilters.onSale && (
          <span className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600">
            On Sale
            <button 
              onClick={() => onClearFilter('onSale')}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              &times;
            </button>
          </span>
        )}
        
        {activeFilters.categories?.map(category => (
          <span key={category} className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600">
            {category}
            <button 
              onClick={() => onClearFilter('category', category)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              &times;
            </button>
          </span>
        ))}
        
        {activeFilters.brands?.map(brand => (
          <span key={brand} className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600">
            {brand}
            <button 
              onClick={() => onClearFilter('brand', brand)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              &times;
            </button>
          </span>
        ))}
        
        {activeFilters.ratings?.map(rating => (
          <span key={rating} className="inline-flex items-center px-3 py-1 rounded-full text-xs bg-rose-50 text-rose-600">
            {rating}+ Stars
            <button 
              onClick={() => onClearFilter('rating', rating)}
              className="ml-1 text-rose-400 hover:text-rose-700"
            >
              &times;
            </button>
          </span>
        ))}
      </div>
    </div>
  );
};

interface SideBarProps {}

const SideBar: React.FC<SideBarProps> = () => {
  const [isOpen, setIsOpen] = useState<boolean>(false);
  const [priceRange, setPriceRange] = useState<[number, number]>([30, 200]);
  const [expandedSections, setExpandedSections] = useState({
    categories: true,
    brands: false,
    price: true,
    ratings: false
  });
  
  // State for active filters
  const [activeFilters, setActiveFilters] = useState<{
    priceRange: [number, number];
    categories: string[];
    brands: string[];
    ratings: number[];
    onSale: boolean;
  }>({
    priceRange: [30, 200],
    categories: [],
    brands: [],
    ratings: [],
    onSale: false
  });

  const toggleSidebar = (): void => setIsOpen(!isOpen);

  const toggleSection = (section: string): void => {
    setExpandedSections({
      ...expandedSections,
      [section]: !expandedSections[section as keyof typeof expandedSections]
    });
  };

  const handleClearFilter = (filterType: string, value?: any) => {
    if (filterType === 'all') {
      setActiveFilters({
        priceRange: [30, 200],
        categories: [],
        brands: [],
        ratings: [],
        onSale: false
      });
      setPriceRange([30, 200]);
      return;
    }
    
    if (filterType === 'priceRange') {
      setActiveFilters({...activeFilters, priceRange: [30, 200]});
      setPriceRange([30, 200]);
    } 
    else if (filterType === 'onSale') {
      setActiveFilters({...activeFilters, onSale: false});
    }
    else if (filterType === 'category' && value) {
      setActiveFilters({
        ...activeFilters, 
        categories: activeFilters.categories.filter(cat => cat !== value)
      });
    }
    else if (filterType === 'brand' && value) {
      setActiveFilters({
        ...activeFilters, 
        brands: activeFilters.brands.filter(brand => brand !== value)
      });
    }
    else if (filterType === 'rating' && value) {
      setActiveFilters({
        ...activeFilters, 
        ratings: activeFilters.ratings.filter(rating => rating !== value)
      });
    }
  };

  const handlePriceChange = (value: [number, number]) => {
    setPriceRange(value);
    setActiveFilters({...activeFilters, priceRange: value});
  };

  const handleCategoryToggle = (category: string) => {
    if (activeFilters.categories.includes(category)) {
      setActiveFilters({
        ...activeFilters,
        categories: activeFilters.categories.filter(cat => cat !== category)
      });
    } else {
      setActiveFilters({
        ...activeFilters,
        categories: [...activeFilters.categories, category]
      });
    }
  };

  const handleBrandToggle = (brand: string) => {
    if (activeFilters.brands.includes(brand)) {
      setActiveFilters({
        ...activeFilters,
        brands: activeFilters.brands.filter(b => b !== brand)
      });
    } else {
      setActiveFilters({
        ...activeFilters,
        brands: [...activeFilters.brands, brand]
      });
    }
  };

  const handleRatingToggle = (rating: number) => {
    if (activeFilters.ratings.includes(rating)) {
      setActiveFilters({
        ...activeFilters,
        ratings: activeFilters.ratings.filter(r => r !== rating)
      });
    } else {
      setActiveFilters({
        ...activeFilters,
        ratings: [...activeFilters.ratings, rating]
      });
    }
  };

  const handleOnSaleToggle = () => {
    setActiveFilters({
      ...activeFilters,
      onSale: !activeFilters.onSale
    });
  };

  // Calculate total active filters
  const totalActiveFilters = 
    activeFilters.categories.length +
    activeFilters.brands.length +
    activeFilters.ratings.length +
    (activeFilters.onSale ? 1 : 0) +
    (activeFilters.priceRange[0] !== 30 || activeFilters.priceRange[1] !== 200 ? 1 : 0);

  // Add a class to body when sidebar is open on mobile
  useEffect(() => {
    if (isOpen) {
      document.body.classList.add('overflow-hidden');
    } else {
      document.body.classList.remove('overflow-hidden');
    }
    return () => {
      document.body.classList.remove('overflow-hidden');
    };
  }, [isOpen]);

  const categories = [
    { icon: <GiLipstick size={20} />, label: "Lips", count: 127 },
    { icon: <GiFaceToFace size={20} />, label: "Face", count: 84 },
    { icon: <GiEyeTarget size={20} />, label: "Eyes", count: 93 },
    { icon: <GiFragrance size={20} />, label: "Fragrance", count: 45 },
    { icon: <FaRegHeart size={20} />, label: "Skincare", count: 216 }
  ];

  const brands = ['Fenty Beauty', 'Glossier', 'Charlotte Tilbury', 'Rare Beauty', 'Dior'];

  return (
    <div>
      {/* Mobile overlay */}
      {isOpen && (
        <div 
          className="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm z-10 md:hidden transition-opacity duration-300"
          onClick={toggleSidebar}
        ></div>
      )}

      {/* Hamburger Icon for Mobile View */}
      <div className="md:hidden p-4">
        <button
          onClick={toggleSidebar}
          className="flex items-center gap-2 fixed top-24 left-4 z-20 p-2 bg-white rounded-full shadow-md hover:shadow-lg transition-shadow"
          aria-label="Toggle sidebar"
        >
          <FaBars className="text-rose-500" />
          {totalActiveFilters > 0 && (
            <span className="absolute -top-1 -right-1 bg-rose-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
              {totalActiveFilters}
            </span>
          )}
        </button>
      </div>

      {/* Sidebar */}
      <div
        className={`fixed top-0 left-0 h-full bg-white z-20 transition-all duration-300 ease-in-out ${
          isOpen ? "translate-x-0 shadow-2xl" : "-translate-x-full"
        } md:static md:translate-x-0 w-80 md:w-full p-6 md:p-4 lg:p-6 overflow-y-auto shadow-lg md:shadow-none`}
      >
        <div className="text-gray-800">
          {/* Close Button for Mobile View */}
          <div className="md:hidden mb-6 flex justify-between items-center">
            <div className={italiana.className}>
              <h2 className="text-2xl font-bold">Filters</h2>
            </div>
            <button
              onClick={toggleSidebar}
              className="text-xl text-gray-800 p-2 hover:bg-gray-100 rounded-full transition-colors"
              aria-label="Close sidebar"
            >
              &times;
            </button>
          </div>

          {/* Filter header for desktop */}
          <div className="hidden md:flex items-center gap-2 mb-6">
            <div className="bg-rose-100 p-2 rounded-full">
              <FaFilter className="text-rose-500" size={16} />
            </div>
            <div className={italiana.className}>
              <h2 className="text-2xl font-bold">Filters</h2>
            </div>
            {totalActiveFilters > 0 && (
              <span className="ml-2 bg-rose-100 text-rose-600 text-xs px-2 py-0.5 rounded-full">
                {totalActiveFilters}
              </span>
            )}
          </div>

          {/* Active Filters Section */}
          <ActiveFilters 
            activeFilters={activeFilters}
            onClearFilter={handleClearFilter}
          />

          {/* Categories Section */}
          <div className="mb-6 border-b border-gray-200 pb-6">
            <SectionHeader 
              title="Categories" 
              isExpanded={expandedSections.categories}
              toggleExpanded={() => toggleSection('categories')}
              count={activeFilters.categories.length}
            />

            {expandedSections.categories && (
              <div className="mt-3 space-y-1">
                {categories.map((category) => (
                  <div 
                    key={category.label}
                    className="py-1.5"
                  >
                    <Checkbox
                      id={`category-${category.label.toLowerCase()}`}
                      checked={activeFilters.categories.includes(category.label)}
                      onChange={() => handleCategoryToggle(category.label)}
                      label={
                        <div className="flex items-center justify-between w-full">
                          <div className="flex items-center gap-3">
                            <span className="text-rose-400">{category.icon}</span>
                            <span>{category.label}</span>
                          </div>
                          <span className="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">
                            {category.count}
                          </span>
                        </div>
                      }
                    />
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Price Range Section */}
          <div className="mb-6 border-b border-gray-200 pb-6">
            <SectionHeader 
              title="Price Range" 
              isExpanded={expandedSections.price}
              toggleExpanded={() => toggleSection('price')}
            />

            {expandedSections.price && (
              <div className="mt-4">
                <PriceFilter
                  min={0}
                  max={300}
                  value={priceRange}
                  onChange={handlePriceChange}
                />
              </div>
            )}
          </div>

          {/* Brands Section */}
          <div className="mb-6 border-b border-gray-200 pb-6">
            <SectionHeader 
              title="Brands" 
              isExpanded={expandedSections.brands}
              toggleExpanded={() => toggleSection('brands')}
              count={activeFilters.brands.length}
            />

            {expandedSections.brands && (
              <div className="mt-3 space-y-1">
                {brands.map((brand) => (
                  <Checkbox
                    key={brand}
                    id={brand.toLowerCase().replace(/\s/g, '-')}
                    checked={activeFilters.brands.includes(brand)}
                    onChange={() => handleBrandToggle(brand)}
                    label={brand}
                  />
                ))}
                <button className="text-sm text-rose-500 mt-4 hover:text-rose-600 flex items-center gap-1">
                  <span>Show more</span>
                  <FaChevronDown size={10} />
                </button>
              </div>
            )}
          </div>

          {/* Ratings Section */}
          <div className="mb-6 border-b border-gray-200 pb-6">
            <SectionHeader 
              title="Ratings" 
              isExpanded={expandedSections.ratings}
              toggleExpanded={() => toggleSection('ratings')}
              count={activeFilters.ratings.length}
            />

            {expandedSections.ratings && (
              <div className="mt-3 space-y-3">
                {[4, 3, 2, 1].map((rating) => (
                  <Checkbox
                    key={rating}
                    id={`rating-${rating}`}
                    checked={activeFilters.ratings.includes(rating)}
                    onChange={() => handleRatingToggle(rating)}
                    label={
                      <div className="flex items-center">
                        {Array(5).fill(0).map((_, i) => (
                          <FaStar 
                            key={i} 
                            className={`w-4 h-4 ${i < rating ? 'text-amber-400' : 'text-gray-300'}`} 
                          />
                        ))}
                        <span className="ml-1 text-sm text-gray-600">& Up</span>
                      </div>
                    }
                  />
                ))}
              </div>
            )}
          </div>

          {/* On Sale Section */}
          <div className="mb-8">
            <Checkbox
              id="on-sale"
              checked={activeFilters.onSale}
              onChange={handleOnSaleToggle}
              label={
                <div className="flex items-center text-sm font-medium">
                  <FaTags className="text-rose-500 mr-2" />
                  <span>On Sale</span>
                  <span className="ml-1 text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full">42</span>
                </div>
              }
            />
          </div>

          {/* Clear All Button */}
          <button 
            onClick={() => handleClearFilter('all')}
            className="w-full py-3 px-4 bg-gradient-to-r from-rose-400 to-rose-500 text-white rounded-lg hover:from-rose-500 hover:to-rose-600 transition-colors duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 font-medium"
          >
            Clear All Filters
            {totalActiveFilters > 0 && (
              <span className="bg-white bg-opacity-30 rounded-full h-5 w-5 flex items-center justify-center text-xs">
                {totalActiveFilters}
              </span>
            )}
          </button>
        </div>
      </div>
    </div>
  );
};

export default SideBar;