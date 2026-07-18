<?php

defined( 'ABSPATH' ) || exit;

class CWFW_Withdrawal {

    /**
     * Check if an order is eligible.
     *
     * @param WC_Order $order
     * @return bool
     */
    public function can_withdraw( WC_Order $order ) {

        // Plugin disabled.
        if ( ! cwfw_is_enabled() ) {
            return false;
        }
    
        // Only completed orders.
        if ( ! $order->has_status( 'completed' ) ) {
            return false;
        }
    
        // Request already exists.
        if ( $order->get_meta( '_cwfw_request' ) ) {
            return false;
        }
    
        // Guest orders.
        if (
            0 === (int) $order->get_user_id() &&
            'yes' !== get_option( 'cwfw_guest_requests', 'yes' )
        ) {
            return false;
        }
    
        // Withdrawal period.
        $period = absint( get_option( 'cwfw_period', 14 ) );
    
        $completed = $order->get_date_completed();
    
        if ( ! $completed ) {
            return false;
        }
    
        if ( $completed->getTimestamp() < strtotime( "-{$period} days" ) ) {
            return false;
        }
    
        return true;
    
    }

}