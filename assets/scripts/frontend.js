(() => {
	"use strict";

	/**
	 * Optional global config to be localized by WP.
	 *
	 * window.FilteringRT = {
	 *   ajaxUrl: "https://example.com/wp-admin/admin-ajax.php",
	 *   nonce: "...." // optional
	 * };
	 */
	const GLOBAL = window.FilteringRT || {};
	const DEFAULT_AJAX_URL = "/wp-admin/admin-ajax.php";

	class RecommendationTool {
		constructor(wrapper) {
			this.wrapper = wrapper;

			this.stepsParent = wrapper.querySelector(".rt-quiz-steps");
			this.steps = this.stepsParent
				? Array.from(this.stepsParent.querySelectorAll(".rt-step"))
				: [];

			this.indicatorsParent = wrapper.querySelector(".rt-quiz-indicators");
			this.indicators = this.indicatorsParent
				? Array.from(this.indicatorsParent.querySelectorAll(".rt-quiz-indicator"))
				: [];

			this.restartBtn = wrapper.querySelector(".rt-quiz-restart");
			this.backBtn = wrapper.querySelector(".rt-quiz-back");

			// State
			this.currentStepIndex = 0; // 0-based index
			this.selections = {}; // key -> value

			// Guard: no steps, nothing to do.
			if (!this.steps.length) {
				return;
			}

			this.bindEvents();
			this.activateStep(0);
			this.refreshIndicators();
			this.refreshBackButton();
		}

		bindEvents() {
			// Option clicks
			this.steps.forEach((stepEl) => {
				const optionsParent = stepEl.querySelector(".rt-step-options");
				if (!optionsParent) {
					return;
				}

				optionsParent.addEventListener("click", (e) => {
					const option = e.target.closest(".rt-step-option");
					if (!option) {
						return;
					}

					this.handleOptionSelect(option);
				});
			});

			// Restart
			if (this.restartBtn) {
				this.restartBtn.addEventListener("click", (e) => {
					e.preventDefault();
					this.restart();
				});
			}

			// Back
			if (this.backBtn) {
				this.backBtn.addEventListener("click", (e) => {
					e.preventDefault();
					this.goBack();
				});
			}

			// Optional: clicking indicator jumps to step (safe, but you can remove).
			if (this.indicatorsParent) {
				this.indicatorsParent.addEventListener("click", (e) => {
					const indicator = e.target.closest(".rt-quiz-indicator");
					if (!indicator) {
						return;
					}

					const stepAttr = indicator.getAttribute("data-step");
					const stepNum = stepAttr ? parseInt(stepAttr, 10) : NaN;
					if (!Number.isFinite(stepNum) || stepNum < 1) {
						return;
					}

					// Only allow jumping to previous steps or current.
					const targetIndex = stepNum - 1;
					if (targetIndex <= this.currentStepIndex) {
						this.activateStep(targetIndex);
					}
				});
			}
		}

		handleOptionSelect(optionEl) {
			const value = optionEl.getAttribute("data-match-selectitem");
			const key = optionEl.getAttribute("data-match-step");

			if (!value || !key) {
				return;
			}

			// Store selection by key.
			this.selections[key] = value;

			// Mark the radio checked if present.
			const input = optionEl.querySelector('input[type="radio"], input[type="checkbox"]');
			if (input) {
				input.checked = true;
			}

			// Update indicator text for this step if possible.
			this.refreshIndicators();

			// Move next
			this.goNext();
		}

		goNext() {
			const nextIndex = this.currentStepIndex + 1;

			if (nextIndex >= this.steps.length) {
				return;
			}

			this.activateStep(nextIndex);
		}

		goBack() {
			const prevIndex = this.currentStepIndex - 1;
			if (prevIndex < 0) {
				return;
			}

			this.activateStep(prevIndex);
		}

		restart() {
			this.selections = {};

			// Uncheck any inputs inside options.
			this.wrapper.querySelectorAll(".rt-step-option input").forEach((input) => {
				input.checked = false;
			});

			// Reset indicator inner text to defaults.
			this.refreshIndicators(true);

            if (this.restartBtn) {
                this.restartBtn.classList.add("rt--is-hidden");
            }

			this.activateStep(0);
		}

		activateStep(index) {
			this.currentStepIndex = index;

			// Toggle active class on steps.
			this.steps.forEach((step, i) => {
				step.classList.toggle("rt--is-active", i === index);
			});

			// Toggle active class on indicators by data-step.
			this.indicators.forEach((indicator) => {
				const stepAttr = indicator.getAttribute("data-step");
				const stepNum = stepAttr ? parseInt(stepAttr, 10) : NaN;
				const indicatorIndex = Number.isFinite(stepNum) ? stepNum - 1 : -1;

				indicator.classList.toggle("rt--is-active", indicatorIndex === index);
			});

			this.refreshBackButton();

            // Reveal restart button after the form proceeds past step 1.
            if (this.restartBtn && index > 0) {
                this.restartBtn.classList.remove("rt--is-hidden");
            }

			const stepEl = this.steps[index];
			if (stepEl && stepEl.classList.contains("js-rt-step--results")) {
				this.loadResultsInto(stepEl);
			}
		}

		refreshBackButton() {
			if (!this.backBtn) {
				return;
			}

			this.backBtn.disabled = this.currentStepIndex <= 0;
		}

		/**
		 * Update indicators:
		 * - If selected value for a step exists, show it.
		 * - Otherwise show data-name-default.
		 *
		 * The mapping here is:
		 * indicator[data-step="1"] corresponds to step index 0, etc.
		 *
		 * The selected value shown is derived by:
		 * - finding selected option within that step, and reading data-match-selectitem.
		 * This avoids guessing how keys map to step numbers.
		 *
		 * @param {boolean} forceDefaults
		 */
		refreshIndicators(forceDefaults = false) {
			if (!this.indicators.length) {
				return;
			}

			this.indicators.forEach((indicator) => {
				const stepAttr = indicator.getAttribute("data-step");
				const stepNum = stepAttr ? parseInt(stepAttr, 10) : NaN;

				const defaultName = indicator.getAttribute("data-name-default") || "";
				const inner = indicator.querySelector(".rt-quiz-indicator-inner");

				if (!inner || !Number.isFinite(stepNum) || stepNum < 1) {
					return;
				}

				if (forceDefaults) {
					inner.textContent = defaultName;
					return;
				}

				const stepIndex = stepNum - 1;
				const stepEl = this.steps[stepIndex];

				if (!stepEl) {
					inner.textContent = defaultName;
					return;
				}

				// Find checked input within this step and get its label's data-match-selectitem.
				const checked = stepEl.querySelector(".rt-step-option input:checked");
				if (checked) {
					const option = checked.closest(".rt-step-option");
					const value = option ? option.getAttribute("data-match-selectitem") : null;

					inner.textContent = value || defaultName;
					return;
				}

				inner.textContent = defaultName;
			});
		}

		getAjaxUrl() {
			return GLOBAL.ajaxUrl || DEFAULT_AJAX_URL;
		}

		getNonce() {
			return GLOBAL.nonce || this.wrapper.getAttribute("data-nonce") || "";
		}

		/**
		 * Replace entire results step content with AJAX response.
		 *
		 * @param {HTMLElement} resultsStep
		 */
		async loadResultsInto(resultsStep) {
			// Basic loading state.
			resultsStep.innerHTML = `
				<div class="rt-results-loading">
					<div class="rt-results-loading-item">
						<div class="rt-spinner"></div>
						<strong>Loading results...</strong>
					</div>
				</div>
			`;

			const formData = new FormData();
			formData.append("action", "filtering_get_result");
			formData.append("selections", JSON.stringify(this.selections));

			const nonce = this.getNonce();
			if (nonce) {
				// Common WP convention (you can rename server-side).
				formData.append("_ajax_nonce", nonce);
			}

			try {
				const res = await fetch(this.getAjaxUrl(), {
					method: "POST",
					credentials: "same-origin",
					body: formData,
				});

				const text = await res.text();

				// Expect HTML from server.
				resultsStep.innerHTML = text;
			} catch (err) {
				resultsStep.innerHTML = `
					<div class="rt-results-nodata">
						Couldn't load results. Please try again.
					</div>
				`;
			}
		}
	}

	/**
	 * Boot all instances.
	 *
	 * Your markup mentions:
	 * .rt-wrapper.js-rt-wrapper
	 */
	function initRecommendationTools() {
		const wrappers = document.querySelectorAll(".js-rt-wrapper, .rt-wrapper");

		wrappers.forEach((wrapper) => {
			// Avoid double init.
			if (wrapper.__filteringRtInstance) {
				return;
			}

			wrapper.__filteringRtInstance = new RecommendationTool(wrapper);
		});
	}

	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initRecommendationTools);
	} else {
		initRecommendationTools();
	}
})();
