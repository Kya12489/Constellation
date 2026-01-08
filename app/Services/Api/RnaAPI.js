import axios from 'axios';

/**
 * Classe pour interagir avec l'API RNA (Répertoire National des Associations)
 */
class RnaAPI {
    constructor() {
        this.baseUrl = 'https://hub.huwise.com/api/explore/v2.1/catalog/datasets/ref-france-association-repertoire-national/records';
    }

    /**
     * Recherche des associations avec pagination et filtres
     * @param {number} page - Numéro de page (commence à 0)
     * @param {number} limit - Nombre de résultats par page
     * @param {Object} filters - Filtres à appliquer (ex: {title: "sport"})
     * @returns {Promise<Object>} - Résultats de la recherche
     */
    async searchAssociations(page = 0, limit = 10, filters = {}) {
        try {
            const params = {
                offset: page * limit,
                limit: limit,
            };

            if (filters.title) {
                params.where = `title LIKE '%${filters.title}%'`;
            }

            
            if (filters.keyWords) {
               
                if (params.where) {
                    params.where += ` AND object LIKE '%${filters.keyWords}%'`;
                } else {
                    params.where = `object LIKE '%${filters.keyWords}%'`;
                }
            }
            
            if (filters.city) {
                if (params.where) {
                    params.where += ` AND com_name_asso LIKE '%${filters.city}%'`;
                } else {
                    params.where = `com_name_asso LIKE '%${filters.city}%'`;
                }
            }

            const response = await axios.get(this.baseUrl, { params });

            return {
                success: true,
                results: response.data.results || [],
                total: response.data.total_count || 0,
                page: page,
            };
        } catch (error) {
            console.error('RNA API Error:', error);
            
            return {
                success: false,
                error: this._formatError(error),
                results: [],
                total: 0,
                page: page,
            };
        }
    }

    /**
     * Récupère une association par son ID
     * @param {string} id - ID de l'association
     * @returns {Promise<Object>}
     */
    async getAssociationById(id) {
    try {
        const params = {
            where: `id = '${id}'`,
            limit: 1,
        };
        
        const response = await axios.get(this.baseUrl, { params });
        const results = response.data.results || [];
        
        return {
            success: true,
            data: results[0] || null, // Retourner directement l'objet
            found: results.length > 0,
        };
    } catch (error) {
        
        return {
            success: false,
            error: this._formatError(error),
            data: null,
            found: false,
        };
    }
}

    async getRateData(rnaId) {
        try {
            const response = await axios.get(`/api/asso/note/${rnaId}`);
            
            return {
                success: true,
                averageRating: response.data.averageRating,
                totalRatings: response.data.totalRatings
            };
        } catch (error) {
            console.error('Rating API Error:', error);
            
            return {
                success: false,
                averageRating: null,
                totalRatings: 0
            };
        }
    }
    /**
     * Valide une URL
     * @param {string} url - URL à valider
     * @returns {boolean}
     */
    isUrlValid(url) {
        if (!url || typeof url !== 'string') {
            return false;
        }

        try {
            const urlObj = new URL(url.trim());
            return urlObj.protocol === 'http:' || urlObj.protocol === 'https:';
        } catch {
            return false;
        }
    }

    /**
     * Traite les sites web des associations
     * @param {Array} associations - Liste des associations
     * @returns {Array} - Associations avec sites web validés
     */
    processWebsites(associations) {
        return associations.map(asso => {
            if (asso.website && asso.website.trim()) {
                const url = asso.website.trim();
                if (this.isUrlValid(url)) {
                    asso.website = url;
                } else {
                    asso.website = 'Invalid';
                }
            } else {
                asso.website = null;
            }
            return asso;
        });
    }

    /**
     * Formate les erreurs de manière cohérente
     * @private
     */
    _formatError(error) {
        if (error.response) {
            // Erreur de réponse du serveur
            return {
                type: 'server_error',
                status: error.response.status,
                message: error.response.data?.message || 'Erreur serveur',
                data: error.response.data,
            };
        } else if (error.request) {
            // Pas de réponse reçue (problème réseau ou CORS)
            return {
                type: 'network_error',
                message: 'Impossible de contacter le serveur. Vérifiez votre connexion ou les paramètres CORS.',
            };
        } else {
            // Erreur dans la configuration de la requête
            return {
                type: 'request_error',
                message: error.message || 'Erreur lors de la préparation de la requête',
            };
        }
    }

    /**
     * Récupère les statistiques générales
     * @returns {Promise<Object>}
     */
    async getStats() {
        try {
            // Récupère juste le total sans résultats
            const response = await axios.get(this.baseUrl, {
                params: { limit: 0 }
            });

            return {
                success: true,
                total: response.data.total_count || 0,
            };
        } catch (error) {
            console.error('RNA API Error:', error);
            
            return {
                success: false,
                error: this._formatError(error),
                total: 0,
            };
        }
    }
}

// Export singleton
export default new RnaAPI();