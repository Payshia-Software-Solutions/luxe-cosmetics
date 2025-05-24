"use client";
import React, { useState, useEffect } from "react";
import ContactForm from "@/components/CheckOut/ContactForm";
import DeliveryForm from "@/components/CheckOut/DeliveryForm";
import ShippingMethod from "@/components/CheckOut/ShippingMethod";
import PaymentOptions from "@/components/CheckOut/PaymentOptions";
import BillingAddressForm from "@/components/CheckOut/BillingAddressForm";
import PaymentNotice from "@/components/CheckOut/PaymentNotice";
import OrderSummary from "@/components/CheckOut/OrderSummary";
import { useCart } from "@/components/CartContext"; // Import the useCart hook
import config from "@/config";
import { ToastContainer, toast } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

import { Address, OrderData, ContactDetails } from "@/types/Checkout";
import { CartItem } from "@/types/CartItem"; // Adjust the import path as necessary

const CheckoutPage: React.FC = () => {
  // Use the cart context instead of local state for cart items
  const { cartItems, clearCart, getTotalAmount } = useCart();

  const [selectedPaymentMethod, setSelectedPaymentMethod] =
    useState<string>("card");
  const [deliveryAddress, setDeliveryAddress] = useState<Address>(
    {} as Address
  );
  const [billingAddress, setBillingAddress] = useState<Address>({} as Address);
  const [finalAmount, setFinalPayAmount] = useState<number>(0);
  const [discountAmount, setDiscountAmount] = useState<number>(0);
  const [shippingFee] = useState<number>(0);
  const [sameAddressStatus, setSameAddressStatus] = useState<number>(1);
  const [contactDetails, setContactDetails] = useState<ContactDetails>({
    email: "",
    subscribe: false,
  });
  const [promoCode, setPromoCode] = useState<string | number>(0);
  const [processing, setProcessing] = useState<boolean>(false);

  useEffect(() => {
    // Get total amount from cart context
    const cartTotal = getTotalAmount();
    setFinalPayAmount(cartTotal);
  }, [cartItems, getTotalAmount]);

  console.log("Cart Items:", cartItems);
  console.log("Cart Total:", getTotalAmount());

  const handlePayment = async (): Promise<void> => {
    if (processing) return; // Prevent multiple clicks
    setProcessing(true); // Disable button and show loading

    // Validate contact details
    if (!contactDetails.email || !validateEmail(contactDetails.email)) {
      toast.error("Please enter a valid email address.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Validate delivery address
    if (
      !deliveryAddress.firstName ||
      !deliveryAddress.lastName ||
      !deliveryAddress.address ||
      !deliveryAddress.city ||
      !deliveryAddress.postalCode
    ) {
      toast.error("Please fill in all required fields for delivery.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Validate billing address if applicable
    if (
      sameAddressStatus === 0 &&
      (!billingAddress.firstName ||
        !billingAddress.lastName ||
        !billingAddress.address ||
        !billingAddress.city ||
        !billingAddress.postalCode)
    ) {
      toast.error("Please fill in all required fields for billing address.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Validate promo code if provided
    if (promoCode && !isValidPromoCode(String(promoCode))) {
      toast.error("Invalid promo code.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Validate payment method
    if (!selectedPaymentMethod) {
      toast.error("Please select a payment method.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Check if cart is empty
    if (cartItems.length === 0) {
      toast.error("Your cart is empty. Please add items before checkout.", {
        position: "top-right",
        autoClose: 3000,
      });

      setProcessing(false);
      return;
    }

    // Collect data from forms
    const orderData: OrderData = {
      items: cartItems as CartItem[], // Type assertion here
      totalAmount: finalAmount,
      discountAmount: discountAmount,
      shippingFee: shippingFee,
      promoCode: promoCode,
      paymentMethod: selectedPaymentMethod,
      contactDetails: contactDetails,
      shippingAddress: deliveryAddress,
      billingAddress:
        sameAddressStatus === 1 ? deliveryAddress : billingAddress,
      sameAddressStatus: sameAddressStatus,
    };

    try {
      if (selectedPaymentMethod === "cod") {
        // COD order logic
        const response = await fetch(
          `${config.API_BASE_URL}/payment/initiate-cod-invoice`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(orderData),
          }
        );

        if (response.status === 201) {
          const data = await response.json();
          const invoiceNumber = data.invoice_id || data.order_id;

          toast.success("Order placed successfully!", {
            position: "top-right",
            autoClose: 3000,
          });

          // Clear the cart after successful order
          clearCart();

          // Redirect to order confirmation page
          window.location.href = `/order-confirmation?order_id=${invoiceNumber}`;
        } else {
          throw new Error("Failed to create COD invoice.");
        }
      } else {
        const response = await fetch(
          `${config.API_BASE_URL}/payment/initiate-payment`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify(orderData),
          }
        );

        if (!response.ok) {
          throw new Error("Order creation failed");
        }

        const html = await response.text(); // Read the response as HTML text

        // Create a new div element to inject the HTML into the page
        const iframeContainer = document.createElement("div");
        iframeContainer.innerHTML = html;

        // Check if the response contains the form and submit it
        const form = iframeContainer.querySelector("form");
        if (form) {
          document.body.appendChild(form); // Append the form to the document body
          form.submit(); // Submit the form to initiate the payment

          // Note: Cart will be cleared after successful payment in the response handler
        } else {
          toast.error("Failed to initiate payment.", {
            position: "top-right",
            autoClose: 3000,
          });
        }
      }
    } catch (error: unknown) {
      console.error("Error:", error);
      toast.error("There was an issue processing your order.", {
        position: "top-right",
        autoClose: 3000,
      });
    } finally {
      setProcessing(false); // Re-enable button after processing
    }
  };

  // Helper function to validate email
  const validateEmail = (email: string): boolean => {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
  };

  // Helper function to validate promo code
  const isValidPromoCode = (code: string): boolean => {
    // Example validation: check if promo code has at least 5 characters
    return code.length >= 5;
  };

  return (
    <section className="">
      <div className="w-full container mx-auto">
        <div className="flex flex-col md:flex-row">
          {/* Left Column */}
          <div className="w-full md:w-1/2 p-4">
            <ContactForm setContactDetails={setContactDetails} />
            <DeliveryForm setDeliveryAddress={setDeliveryAddress} />
            <BillingAddressForm
              shippingAddress={deliveryAddress}
              setBillingAddress={setBillingAddress}
              setSameAddressStatus={setSameAddressStatus}
            />
            <ShippingMethod />
          </div>
          {/* Right Column */}
          <div className="w-full md:w-1/2 p-4">
            <OrderSummary
              cart={cartItems}
              finalAmount={finalAmount}
              shippingFee={shippingFee}
              setPromoCode={setPromoCode}
              setFinalPayAmount={setFinalPayAmount}
              setDiscountAmount={setDiscountAmount}
            />

            <PaymentOptions
              setSelectedPaymentMethod={setSelectedPaymentMethod}
            />
            <PaymentNotice />

            <div className="mx-auto p-6 bg-white">
              <button
                onClick={handlePayment}
                disabled={processing || cartItems.length === 0}
                className={`w-full px-6 py-3 text-white rounded-lg text-center font-semibold ${
                  processing || cartItems.length === 0
                    ? "bg-gray-400"
                    : "bg-black hover:bg-gray-800"
                }`}
              >
                {processing ? "Processing..." : "Pay now"}
              </button>
            </div>
          </div>
        </div>
      </div>

      {/* Overlay */}
      {processing && (
        <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div className="bg-white rounded-lg p-6 shadow-lg flex flex-col items-center">
            <div className="loader border-t-4 border-gray-800 rounded-full w-16 h-16 animate-spin mb-4"></div>
            <p className="text-lg font-medium">Processing your order...</p>
          </div>
        </div>
      )}
      <ToastContainer />
    </section>
  );
};

export default CheckoutPage;
