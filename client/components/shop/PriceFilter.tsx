import React from "react";
import { PriceFilterProps } from "@/types/shop";

const PriceFilter: React.FC<PriceFilterProps> = ({
  min,
  max,
  value,
  onChange,
}) => {
  const handleMinChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newMin = parseInt(e.target.value);
    if (onChange && newMin <= value[1]) {
      onChange([newMin, value[1]]);
    }
  };

  const handleMaxChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const newMax = parseInt(e.target.value);
    if (onChange && newMax >= value[0]) {
      onChange([value[0], newMax]);
    }
  };

  return (
    <div className="space-y-4">
      <div className="flex justify-between items-center mb-1">
        <div className="px-3 py-1.5 bg-gray-50 dark:bg-[#1e1e1e]  rounded-md border border-gray-200 dark:border-gray-900 w-24 text-center">
          <span className="text-sm font-medium">${value[0]}</span>
        </div>
        <div className="text-gray-400 text-xs">to</div>
        <div className="px-3 py-1.5 bg-gray-50 dark:bg-[#1e1e1e]  rounded-md border border-gray-200 dark:border-gray-900 w-24 text-center">
          <span className="text-sm font-medium">${value[1]}</span>
        </div>
      </div>
      <div className="relative h-2 bg-gray-200 dark:bg-[#1e1e1e]  rounded-full my-6">
        <div
          className="absolute h-2 bg-gradient-to-r from-rose-300 to-rose-500 rounded-full"
          style={{
            left: `${((value[0] - min) / (max - min)) * 100}%`,
            right: `${100 - ((value[1] - min) / (max - min)) * 100}%`,
          }}
        ></div>
        <div
          className="absolute h-5 w-5 bg-white dark:bg-[#1e1e1e]  border-2 border-rose-500 rounded-full -mt-1.5 shadow-md cursor-pointer hover:scale-110 transition-transform"
          style={{
            left: `calc(${((value[0] - min) / (max - min)) * 100}% - 10px)`,
          }}
        ></div>
        <div
          className="absolute h-5 w-5 bg-white dark:bg-[#1e1e1e] border-2 border-rose-500 rounded-full -mt-1.5 shadow-md cursor-pointer hover:scale-110 transition-transform"
          style={{
            left: `calc(${((value[1] - min) / (max - min)) * 100}% - 10px)`,
          }}
        ></div>
      </div>
      <div className="flex gap-4 relative dark:text-gray-50">
        <input
          type="range"
          min={min}
          max={max}
          value={value[0]}
          onChange={handleMinChange}
          className="w-full absolute opacity-0 cursor-pointer z-10 h-2 "
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

export default PriceFilter;
