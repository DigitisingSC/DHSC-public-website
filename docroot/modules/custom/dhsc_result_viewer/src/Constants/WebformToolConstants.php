<?php

namespace Drupal\dhsc_result_viewer\Constants;

/**
 * Defines constants for webform tool identifiers.
 */
class WebformToolConstants {

  /**
   * Debug flag to control logging and on-screen messages.
   */
  public const DEBUG_MODE = TRUE;

  /**
   * Constants defining webform_id values for tools that this module extends.
   */
  public const SELF_ASSESSMENT_TOOL = 'self_assessment_tool';
  public const ASSURED_SOLUTIONS_TOOL = 'assured_solutions_tool';
  public const DSF_TOOL = 'dsf_tool';
  public const DSF_TOOL_ADVANCED = 'dsf_tool_advanced';
  public const WHAT_GOOD_LOOKS_LIKE_TOOL = 'what_good_looks_like_tool';

  public const WEBFORM_TOOLS = [
    self::SELF_ASSESSMENT_TOOL,
    self::ASSURED_SOLUTIONS_TOOL,
    self::DSF_TOOL,
    self::DSF_TOOL_ADVANCED,
    self::WHAT_GOOD_LOOKS_LIKE_TOOL,
  ];

  public const WEBFORM_TOOLS_INDIVIDUAL = [
    self::SELF_ASSESSMENT_TOOL,
    self::ASSURED_SOLUTIONS_TOOL,
  ];

  public const WEBFORM_AUTOSAVE = [
    self::ASSURED_SOLUTIONS_TOOL,
    self::DSF_TOOL,
    self::DSF_TOOL_ADVANCED,
    self::WHAT_GOOD_LOOKS_LIKE_TOOL,
  ];

  public const WEBFORM_TOOLS_THEMED = [
    self::DSF_TOOL,
    self::DSF_TOOL_ADVANCED,
    self::WHAT_GOOD_LOOKS_LIKE_TOOL,
  ];

}
