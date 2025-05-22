"use client";
import React, { useState, useEffect } from "react";
import config from "@/config";
import Image from "next/image";

import { PromoCodeData, ApplicableProductData,OrderSummaryProps } from "@/types/Checkout"; // Adjust the import path as necessary

declare global {
  interface Window {
    dataLayer: Record<string, unknown>[];
    fbq: (track: string, event: string, data: Record<string, unknown>) => void;
  }
}

const OrderSummary: React.FC<OrderSummaryProps> = ({
  cart,
  finalAmount,
  shippingFee,
  setPromoCode,
  setFinalPayAmount,
  setDiscountAmount,
}) => {
  const [coupon, setCoupon] = useState<string>(""); // Entered coupon code
  const [discount, setDiscount] = useState<number>(0); // Discount amount
  const [couponError, setCouponError] = useState<string>(""); // Error message
  const [loading, setLoading] = useState<boolean>(false); // Loading state for validation

  const totalPrice = cart.reduce(
    (sum, item) => sum + item.price * item.quantity,
    0
  );

  // Update final amount when discount or cart changes
  useEffect(() => {
    const newFinalAmount = totalPrice + shippingFee - discount;
    setFinalPayAmount(newFinalAmount);
  }, [totalPrice, shippingFee, discount, setFinalPayAmount]);

  // Push Checkout Data to GTM and Pixel
  useEffect(() => {
    if (typeof window !== "undefined" && cart.length > 0 && totalPrice > 0) {
      // Deduplication logic using localStorage
      const lastGtmEventTimestamp = localStorage.getItem(
        "gtm_checkout_timestamp"
      );
      const now = Date.now();

      // Set a threshold of 1 second to prevent duplicate events
      if (
        !lastGtmEventTimestamp ||
        now - parseInt(lastGtmEventTimestamp) > 1000
      ) {
        // GTM Data Layer Push
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({
          event: "checkout",
          ecommerce: {
            currency: "LKR", // Change if applicable
            value: totalPrice, // Final order total
            shipping_fee: shippingFee, // Shipping fee
            items: cart.map((item) => ({
              item_name: item.name,
              item_id: item.id,
              price: item.price,
              quantity: item.quantity,
            })),
          },
        });

        // Update the timestamp in localStorage
        localStorage.setItem("gtm_checkout_timestamp", now.toString());
      }
    }
  }, [cart, totalPrice, shippingFee]); // Add all relevant dependencies

  useEffect(() => {
    if (
      typeof window !== "undefined" &&
      typeof window.fbq === "function" &&
      cart.length > 0
    ) {
      // Deduplication logic using localStorage
      const lastEventTimestamp = localStorage.getItem("fbq_checkout_timestamp");
      const now = Date.now();

      // Set a threshold of 1 second to prevent duplicate events
      if (!lastEventTimestamp || now - parseInt(lastEventTimestamp) > 1000) {
        // Send the InitiateCheckout event
        window.fbq("track", "InitiateCheckout", {
          content_ids: cart.map((item) => item.id),
          content_type: "product",
          value: finalAmount, // Final amount after discount
          currency: "LKR", // Change if applicable
          shipping_fee: shippingFee, // Shipping fee
          contents: cart.map((item) => ({
            id: item.id,
            quantity: item.quantity,
            price: item.price,
            line_total: item.quantity * item.price,
          })),
        });

        // Update the timestamp in localStorage
        localStorage.setItem("fbq_checkout_timestamp", now.toString());
      }
    }
  }, [cart, finalAmount, shippingFee]); // Add all relevant dependencies

  // Fetch promo code details
  const validateCoupon = async (): Promise<void> => {
    if (!coupon) {
      setCouponError("Please enter a valid coupon code.");
      return;
    }

    setLoading(true);
    setCouponError(""); // Clear any previous errors

    try {
      // Fetch promo code details
      const promoResponse = await fetch(
        `${config.API_BASE_URL}/promo_codes/by_code/${coupon}`
      );
      const promoData: PromoCodeData = await promoResponse.json();

      // Handle if promo code is not active
      if (!promoData.is_active) {
        setCouponError("This promo code is not active.");
        setLoading(false);
        return;
      }

      const now = new Date();
      const startDate = new Date(promoData.start_date);
      const endDate = new Date(promoData.end_date);

      // Check promo code expiry
      if (now < startDate || now > endDate) {
        setCouponError("This promo code is expired.");
        setLoading(false);
        return;
      }

      // Check for minimum order value
      if (totalPrice < parseFloat(promoData.min_order_value)) {
        setCouponError(
          `Minimum order value of Rs. ${promoData.min_order_value} is required.`
        );
        setLoading(false);
        return;
      }

      // Fetch applicable products for the promo code
      const applicableProductsResponse = await fetch(
        `${config.API_BASE_URL}/promo-code-products/get-by-promo-code-active/${coupon}`
      );

      if (!applicableProductsResponse.ok) {
        throw new Error("Failed to fetch applicable products.");
      }

      const applicableProductsData: ApplicableProductData[] =
        await applicableProductsResponse.json();

      // Get applicable product ids
      const applicableProductIds = applicableProductsData.map(
        (product) => product.product_id
      );

      // Find cart products that are not eligible
      const notEligibleProducts = cart.filter(
        (product) => !applicableProductIds.includes(product.id)
      );

      // If some products are not applicable, show an error with the top 3
      if (notEligibleProducts.length > 0) {
        const notEligibleProductNames = notEligibleProducts
          .slice(0, 3) // Take the top 3 products
          .map((product) => product.name); // Get product names
        const additionalMessage =
          notEligibleProducts.length > 3
            ? `...and ${notEligibleProducts.length - 3} more products`
            : "";

        setCouponError(
          `The following products are not eligible for this promo code: ${notEligibleProductNames.join(
            ", "
          )} ${additionalMessage}.`
        );
        setLoading(false);
        return;
      }

      // Calculate discount if all checks pass
      const discountValue =
        promoData.discount_type === "percentage"
          ? (totalPrice * parseFloat(promoData.discount_value)) / 100
          : parseFloat(promoData.discount_value);

      const calculatedFinalPayAmount = totalPrice + shippingFee - discountValue;

      setDiscount(discountValue);
      setPromoCode(coupon);
      setDiscountAmount(discountValue);
      setFinalPayAmount(calculatedFinalPayAmount); // Use the calculated value here
      setCouponError(""); // Clear any errors
    } catch (error) {
      console.error(error); // Log the error to console for debugging
      setCouponError("Failed to validate the coupon code.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="p-6">
      {/* Product Items */}
      {cart.map((item) => (
        <div key={item.id} className="flex items-start mb-4">
          <Image
            src={item.image || "/assets/product/placeholder.jpg"}
            alt={item.name}
            width={64}
            height={64}
            className="object-cover rounded-xl"
          />
          <div
            className="ml-4 flex-1"
            data-product-id={item.id}
            data-product-name={item.name}
            data-product-price={item.price}
          >
            <h4 className="font-medium text-sm">{item.name}</h4>
            <p className="text-sm mt-1">
              <span className="font-semibold text-black">LKR {item.price}</span>
              <span className="ml-2 text-xs text-gray-600">
                x {item.quantity}
              </span>
            </p>
          </div>
          <span className="text-xs flex flex-col items-center justify-center">
            <p className="font-semibold text-black">
              LKR {(item.quantity * item.price).toFixed(2)}
            </p>
          </span>
        </div>
      ))}

      {/* Discount Code Input */}
      <div className="flex mb-4">
        <input
          type="text"
          value={coupon}
          onChange={(e) => setCoupon(e.target.value)}
          placeholder="Discount code or gift card"
          className="w-full p-2 text-black rounded-l-md border border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
        />
        <button
          onClick={validateCoupon}
          disabled={loading}
          className="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-r-md font-semibold"
        >
          {loading ? "Validating..." : "Apply"}
        </button>
      </div>
      {couponError && <p className="text-red-500 text-sm">{couponError}</p>}

      {/* Subtotal and Shipping */}
      <div className="space-y-2 text-sm">
        <div className="flex justify-between">
          <span>Subtotal</span>
          <span
            className="sub-total"
            id="sub-total"
            data-sub-total={totalPrice.toFixed(2)}
          >
            Rs. {totalPrice.toFixed(2)}
          </span>
        </div>
        <div className="flex justify-between">
          <span>Shipping</span>
          <span
            id="shipping-fee"
            className="shipping-fee"
            data-shipping-value={shippingFee.toFixed(2)}
          >
            Rs {shippingFee.toFixed(2)}
          </span>
        </div>
        {discount > 0 && (
          <div className="flex justify-between text-green-500">
            <span>Discount</span>
            <span
              id="discount-amount"
              className="discount-amount"
              data-discount-value={discount.toFixed(2)}
            >
              - Rs {discount.toFixed(2)}
            </span>
          </div>
        )}
      </div>

      {/* Total */}
      <div className="border-t border-gray-700 mt-4 pt-4">
        <div className="flex justify-between items-center font-semibold text-lg">
          <span>Total</span>
          <span
            id="checkout-total"
            className="total-amount"
            data-final-amount={(totalPrice + shippingFee - discount).toFixed(2)}
          >
            Rs {(totalPrice + shippingFee - discount).toFixed(2)}
          </span>
        </div>
      </div>
    </div>
  );
};

export default OrderSummary;
