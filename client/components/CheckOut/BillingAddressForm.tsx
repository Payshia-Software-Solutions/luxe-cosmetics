"use client";
import React, { useState, ChangeEvent } from "react";
import { AddressData,BillingAddressFormProps } from "@/types/Checkout"; // Adjust the import path as necessary


export default function BillingAddressForm({
  shippingAddress,
  setBillingAddress,
  setSameAddressStatus,
}: BillingAddressFormProps) {
  const [useDifferentAddress, setUseDifferentAddress] = useState<boolean>(false);
  const [billingFormData, setBillingFormData] = useState<AddressData>({
    country: "",
    firstName: "",
    lastName: "",
    address: "",
    apartment: "",
    city: "",
    postalCode: "",
    phone: "",
  });

  // Update billing form data
  const handleInputChange = (e: ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    const updatedFormData = { ...billingFormData, [name]: value };
    setBillingFormData(updatedFormData);

    // Update parent state if different billing address is selected
    if (useDifferentAddress) {
      setBillingAddress(updatedFormData);
    }
  };

  // Handle address selection toggle
  const handleAddressToggle = (useDifferent: boolean) => {
    setUseDifferentAddress(useDifferent);
    if (!useDifferent) {
      // Use shipping address as billing address
      setBillingAddress(shippingAddress);
      setSameAddressStatus(1);
    } else {
      // Clear billing address when switching to a different address
      setBillingAddress(billingFormData);
      setSameAddressStatus(0);
    }
  };

  return (
    <div className="mx-auto p-6 bg-white">
      <h2 className="text-lg font-medium mb-4">Billing Address</h2>
      <div className="mb-4">
        <label className="flex items-center space-x-2">
          <input
            type="radio"
            name="billingAddress"
            className="form-radio h-4 w-4 text-blue-600"
            checked={!useDifferentAddress}
            onChange={() => handleAddressToggle(false)}
          />
          <span>Same as shipping address</span>
        </label>
      </div>
      <div className="mb-4">
        <label className="flex items-center space-x-2">
          <input
            type="radio"
            name="billingAddress"
            className="form-radio h-4 w-4 text-blue-600"
            checked={useDifferentAddress}
            onChange={() => handleAddressToggle(true)}
          />
          <span>Use a different billing address</span>
        </label>
      </div>
      {useDifferentAddress && (
        <form>
          <div className="mb-4">
            <label htmlFor="country" className="block text-sm font-medium mb-1">
              Country/Region
            </label>
            <select
              id="country"
              name="country"
              value={billingFormData.country}
              onChange={handleInputChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Country</option>
              <option value="Sri Lanka">Sri Lanka</option>
              {/* Add more options as needed */}
            </select>
          </div>
          <div className="flex gap-4">
            <div className="w-1/2 mb-4">
              <label className="block text-sm font-medium mb-1">
                First name
              </label>
              <input
                type="text"
                name="firstName"
                value={billingFormData.firstName}
                onChange={handleInputChange}
                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div className="w-1/2 mb-4">
              <label className="block text-sm font-medium mb-1">
                Last name
              </label>
              <input
                type="text"
                name="lastName"
                value={billingFormData.lastName}
                onChange={handleInputChange}
                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
          <div className="mb-4">
            <label className="block text-sm font-medium mb-1">Address</label>
            <input
              type="text"
              name="address"
              value={billingFormData.address}
              onChange={handleInputChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div className="mb-4">
            <label className="block text-sm font-medium mb-1">
              Apartment, suite, etc. (optional)
            </label>
            <input
              type="text"
              name="apartment"
              value={billingFormData.apartment}
              onChange={handleInputChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
          <div className="flex gap-4">
            <div className="w-1/2 mb-4">
              <label className="block text-sm font-medium mb-1">City</label>
              <input
                type="text"
                name="city"
                value={billingFormData.city}
                onChange={handleInputChange}
                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
            <div className="w-1/2 mb-4">
              <label className="block text-sm font-medium mb-1">
                Postal Code
              </label>
              <input
                type="text"
                name="postalCode"
                value={billingFormData.postalCode}
                onChange={handleInputChange}
                className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>
          <div className="mb-4">
            <label className="block text-sm font-medium mb-1">
              Phone (optional)
            </label>
            <input
              type="text"
              name="phone"
              value={billingFormData.phone}
              onChange={handleInputChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
            />
          </div>
        </form>
      )}
    </div>
  );
}