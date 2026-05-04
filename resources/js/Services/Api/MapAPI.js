import axios from 'axios';

/**
 * Classe pour gérer les fonctionnalités de carte et géolocalisation
 */
class MapAPI {
    constructor() {
        // API de géocodage gratuite (pas besoin de clé API)
        this.geocodingUrl = 'https://nominatim.openstreetmap.org/search';
        this.reverseGeocodingUrl = 'https://nominatim.openstreetmap.org/reverse';
    }

    /**
     * Convertir une adresse en coordonnées (latitude, longitude)
     * @param {Object} address - Objet contenant les informations d'adresse
     * @returns {Promise<Object>}
     */
    async geocodeAddress(address) {
        try {
            // Construire l'adresse complète
            const addressParts = [];
            
            if (address.street) addressParts.push(address.street);
            if (address.city) addressParts.push(address.city);
            if (address.postalCode) addressParts.push(address.postalCode);
            if (address.country) addressParts.push(address.country);
            
            const fullAddress = addressParts.join(', ');
            
            if (!fullAddress) {
                return {
                    success: false,
                    error: 'Adresse vide'
                };
            }

            console.log('Géocodage de l\'adresse:', fullAddress);

            // Bbox de la France métropolitaine : Sud-Ouest à Nord-Est
            // Coordonnées approximatives: -5.14,41.33,9.56,51.09
            const franceBbox = '-5.14,41.33,9.56,51.09';

            const response = await axios.get(this.geocodingUrl, {
                params: {
                    q: fullAddress,
                    format: 'json',
                    limit: 1,
                    addressdetails: 1,
                    countrycodes: 'fr', // Limiter aux résultats français
                    viewbox: franceBbox, // Zone de recherche prioritaire
                    bounded: 1 // Forcer les résultats dans la viewbox
                },
                headers: {
                    'User-Agent': 'ConstellationApp/1.0'
                }
            });

            if (response.data && response.data.length > 0) {
                const result = response.data[0];
                const lat = parseFloat(result.lat);
                const lon = parseFloat(result.lon);
                
                // Vérifier que les coordonnées sont bien en France
                if (!this._isInFrance(lat, lon)) {
                    console.warn('Coordonnées hors de France:', lat, lon);
                    return {
                        success: false,
                        error: 'Adresse non trouvée en France'
                    };
                }
                
                return {
                    success: true,
                    latitude: lat,
                    longitude: lon,
                    displayName: result.display_name,
                    raw: result
                };
            }

            return {
                success: false,
                error: 'Adresse non trouvée'
            };

        } catch (error) {
            console.error('Geocoding Error:', error);
            return {
                success: false,
                error: error.message
            };
        }
    }

    /**
     * Vérifie si des coordonnées sont en France métropolitaine
     * @private
     * @param {number} lat - Latitude
     * @param {number} lon - Longitude
     * @returns {boolean}
     */
    _isInFrance(lat, lon) {
        // Limites approximatives de la France métropolitaine
        const bounds = {
            minLat: 41.33,  // Corse du Sud
            maxLat: 51.09,  // Nord
            minLon: -5.14,  // Bretagne Ouest
            maxLon: 9.56    // Alpes Est
        };
        
        return lat >= bounds.minLat && 
               lat <= bounds.maxLat && 
               lon >= bounds.minLon && 
               lon <= bounds.maxLon;
    }

    /**
     * Obtenir la position actuelle de l'utilisateur
     * @returns {Promise<Object>}
     */
    async getUserLocation() {
        return new Promise((resolve) => {
            if (!navigator.geolocation) {
                resolve({
                    success: false,
                    error: 'Géolocalisation non supportée par ce navigateur'
                });
                return;
            }

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    
                    // Vérifier que l'utilisateur est en France
                    if (!this._isInFrance(lat, lon)) {
                        resolve({
                            success: false,
                            error: 'Position en dehors de la France',
                            latitude: lat,
                            longitude: lon
                        });
                        return;
                    }
                    
                    resolve({
                        success: true,
                        latitude: lat,
                        longitude: lon,
                        accuracy: position.coords.accuracy
                    });
                },
                (error) => {
                    let errorMessage = 'Erreur de géolocalisation';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = 'Permission de géolocalisation refusée';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = 'Position indisponible';
                            break;
                        case error.TIMEOUT:
                            errorMessage = 'Délai de géolocalisation dépassé';
                            break;
                    }

                    resolve({
                        success: false,
                        error: errorMessage
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }

    /**
     * Calculer la distance entre deux points (en km)
     * @param {number} lat1 - Latitude du point 1
     * @param {number} lon1 - Longitude du point 1
     * @param {number} lat2 - Latitude du point 2
     * @param {number} lon2 - Longitude du point 2
     * @returns {number} Distance en kilomètres
     */
    calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371; // Rayon de la Terre en km
        const dLat = this._toRad(lat2 - lat1);
        const dLon = this._toRad(lon2 - lon1);
        
        const a = 
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(this._toRad(lat1)) * Math.cos(this._toRad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;
        
        return Math.round(distance * 100) / 100; // Arrondir à 2 décimales
    }

    /**
     * Convertir des degrés en radians
     * @private
     */
    _toRad(degrees) {
        return degrees * (Math.PI / 180);
    }

    /**
     * Obtenir les coordonnées d'une association à partir de ses données
     * @param {Object} association - Données de l'association
     * @returns {Promise<Object>}
     */
    async getAssociationCoordinates(association) {
        // Si l'association a déjà des coordonnées
        if (association.latitude && association.longitude) {
            return {
                success: true,
                latitude: parseFloat(association.latitude),
                longitude: parseFloat(association.longitude),
                source: 'database'
            };
        }

        // Sinon, géocoder l'adresse
        const address = {
            street: this._buildStreetAddress(association),
            city: association.com_name_asso,
            postalCode: association.pc_address_asso,
            country: association.country_address_asso || 'France'
        };

        const result = await this.geocodeAddress(address);
        
        if (result.success) {
            return {
                ...result,
                source: 'geocoding'
            };
        }

        return result;
    }

    /**
     * Construire l'adresse de rue à partir des données de l'association
     * @private
     */
    _buildStreetAddress(association) {
        const parts = [];
        
        if (association.street_number_asso) parts.push(association.street_number_asso);
        if (association.street_type_asso) parts.push(association.street_type_asso);
        if (association.street_name_asso) {
            parts.push(association.street_name_asso);
        } else if (association.comp_address_asso) {
            parts.push(association.comp_address_asso);
        }
        
        return parts.join(' ');
    }

    /**
     * Obtenir l'URL de l'image de carte statique (OpenStreetMap)
     * @param {number} lat - Latitude
     * @param {number} lon - Longitude
     * @param {number} zoom - Niveau de zoom (défaut: 15)
     * @returns {string} URL de l'image
     */
    getStaticMapUrl(lat, lon, zoom = 15) {
        // Utiliser l'API StaticMap d'OpenStreetMap
        return `https://www.openstreetmap.org/export/embed.html?bbox=${lon-0.01},${lat-0.01},${lon+0.01},${lat+0.01}&layer=mapnik&marker=${lat},${lon}`;
    }
}

// Export singleton
export default new MapAPI();