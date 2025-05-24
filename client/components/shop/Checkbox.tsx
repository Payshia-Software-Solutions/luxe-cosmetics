import React from "react";
import { CheckboxProps } from "@/types/shop";
const Checkbox: React.FC<CheckboxProps> = ({
  id,
  label,
  checked = false,
  onChange,
}) => {
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
        <div
          className={`border-2 rounded w-5 h-5 flex flex-shrink-0 justify-center items-center mr-2 
          ${
            checked
              ? "bg-rose-500 border-rose-500"
              : "border-gray-300 bg-white dark:bg-[#1e1e1e] dark:border-gray-900"
          }`}
        >
          <svg
            className={`fill-current w-3 h-3 text-white pointer-events-none ${
              checked ? "opacity-100" : "opacity-0"
            }`}
            viewBox="0 0 20 20"
          >
            <path d="M0 11l2-2 5 5L18 3l2 2L7 18z" />
          </svg>
        </div>
      </div>
      <label
        htmlFor={id}
        className="ml-2 cursor-pointer text-gray-400 dark:text-gray-50 text-sm hover:text-rose-500 transition-colors select-none"
      >
        {label}
      </label>
    </div>
  );
};

export default Checkbox;
