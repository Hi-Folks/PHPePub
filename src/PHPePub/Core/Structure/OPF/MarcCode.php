<?php
namespace PHPePub\Core\Structure\OPF;

/**
 * Common Marc codes.
 * Ref: http://www.loc.gov/marc/relators/
 *
 * @author    A. Grandt <php@grandt.com>
 * @copyright 2014- A. Grandt
 * @license   GNU LGPL 2.1
 */
class MarcCode {
    /**
     * Adapter
     *
     * Use for a person who
     * 1) reworks a musical composition, usually for a different medium, or
     * 2) rewrites novels or stories for motion pictures or other audiovisual medium.
     */
    final public const ADAPTER = "adp";

    /**
     * Annotator
     *
     * Use for a person who writes manuscript annotations on a printed item.
     */
    final public const ANNOTATOR = "ann";

    /**
     * Arranger
     *
     * Use for a person who transcribes a musical composition, usually for a different
     *  medium from that of the original; in an arrangement the musical substance remains
     *  essentially unchanged.
     */
    final public const ARRANGER = "arr";

    /**
     * Artist
     *
     * Use for a person (e.g., a painter) who conceives, and perhaps also implements,
     *  an original graphic design or work of art, if specific codes (e.g., [egr],
     *  [etr]) are not desired. For book illustrators, prefer Illustrator [ill].
     */
    final public const ARTIST = "art";

    /**
     * Associated name
     *
     * Use as a general relator for a name associated with or found in an item or
     *  collection, or which cannot be determined to be that of a Former owner [fmo]
     *  or other designated relator indicative of provenance.
     */
    final public const ASSOCIATED_NAME = "asn";

    /**
     * Author
     *
     * Use for a person or corporate body chiefly responsible for the intellectual
     *  or artistic content of a work. This term may also be used when more than one
     *  person or body bears such responsibility.
     */
    final public const AUTHOR = "aut";

    /**
     * Author in quotations or text extracts
     *
     * Use for a person whose work is largely quoted or extracted in a works to which
     *  he or she did not contribute directly. Such quotations are found particularly
     *  in exhibition catalogs, collections of photographs, etc.
     */
    final public const AUTHOR_IN_QUOTES = "aqt";

    /**
     * Author of afterword, colophon, etc.
     *
     * Use for a person or corporate body responsible for an afterword, postface,
     *  colophon, etc. but who is not the chief author of a work.
     */
    final public const AUTHOR_OF_AFTERWORD = "aft";

    /**
     * Author of introduction, etc.
     *
     * Use for a person or corporate body responsible for an introduction, preface,
     *  foreword, or other critical matter, but who is not the chief author.
     */
    final public const AUTHOR_OF_INTRO = "aui";

    /**
     * Bibliographic antecedent
     *
     * Use for the author responsible for a work upon which the work represented by
     *  the catalog record is based. This can be appropriate for adaptations, sequels,
     *  continuations, indexes, etc.
     */
    final public const BIB_ANTECEDENT = "ant";

    /**
     * Book producer
     *
     * Use for the person or firm responsible for the production of books and other
     *  print media, if specific codes (e.g., [bkd], [egr], [tyd], [prt]) are not desired.
     */
    final public const BOOK_PRODUCER = "bkp";

    /**
     * Collaborator
     *
     * Use for a person or corporate body that takes a limited part in the elaboration
     *  of a work of another author or that brings complements (e.g., appendices, notes)
     *  to the work of another author.
     */
    final public const COLABORATOR = "clb";

    /**
     * Commentator
     *
     * Use for a person who provides interpretation, analysis, or a discussion of the
     *  subject matter on a recording, motion picture, or other audiovisual medium.
     *  Compiler [com] Use for a person who produces a work or publication by selecting
     *  and putting together material from the works of various persons or bodies.
     */
    final public const COMMENTATOR = "cmm";

    /**
     * Designer
     *
     * Use for a person or organization responsible for design if specific codes (e.g.,
     *  [bkd], [tyd]) are not desired.
     */
    final public const DESIGNER = "dsr";

    /**
     * Editor
     *
     * Use for a person who prepares for publication a work not primarily his/her own,
     *  such as by elucidating text, adding introductory or other critical matter, or
     *  technically directing an editorial staff.
     */
    final public const EDITORT = "edt";

    /**
     * Illustrator
     *
     * Use for the person who conceives, and perhaps also implements, a design or
     *  illustration, usually to accompany a written text.
     */
    final public const ILLUSTRATOR = "ill";

    /**
     * Lyricist
     *
     * Use for the writer of the text of a song.
     */
    final public const LYRICIST = "lyr";

    /**
     * Metadata contact
     *
     * Use for the person or organization primarily responsible for compiling and
     *  maintaining the original description of a metadata set (e.g., geospatial
     *  metadata set).
     */
    final public const METADATA_CONTACT = "mdc";

    /**
     * Musician
     *
     * Use for the person who performs music or contributes to the musical content
     *  of a work when it is not possible or desirable to identify the function more
     *  precisely.
     */
    final public const MUSICIAN = "mus";

    /**
     * Narrator
     *
     * Use for the speaker who relates the particulars of an act, occurrence, or
     *  course of events.
     */
    final public const NARRATOR = "nrt";

    /**
     * Other
     *
     * Use for relator codes from other lists which have no equivalent in the MARC
     *  list or for terms which have not been assigned a code.
     */
    final public const OTHER = "oth";

    /**
     * Photographer
     *
     * Use for the person or organization responsible for taking photographs, whether
     *  they are used in their original form or as reproductions.
     */
    final public const PHOTOGRAPHER = "pht";

    /**
     * Printer
     *
     * Use for the person or organization who prints texts, whether from type or plates.
     */
    final public const PRINTER = "prt";

    /**
     * Redactor
     *
     * Use for a person who writes or develops the framework for an item without
     *  being intellectually responsible for its content.
     */
    final public const REDACTOR = "red";

    /**
     * Reviewer
     *
     * Use for a person or corporate body responsible for the review of book, motion
     *  picture, performance, etc.
     */
    final public const REVIEWER = "rev";

    /**
     * Sponsor
     *
     * Use for the person or agency that issued a contract, or under whose auspices
     *  a work has been written, printed, published, etc.
     */
    final public const SPONSOR = "spn";

    /**
     * Thesis advisor
     *
     * Use for the person under whose supervision a degree candidate develops and
     *  presents a thesis, memoir, or text of a dissertation.
     */
    final public const THESIS_ADVISOR = "ths";

    /**
     * Transcriber
     *
     * Use for a person who prepares a handwritten or typewritten copy from original
     *  material, including from dictated or orally recorded material.
     */
    final public const TRANSCRIBER = "trc";

    /**
     * Translator
     *
     * Use for a person who renders a text from one language into another, or from
     *  an older form of a language into the modern form.
     */
    final public const TRANSLATOR = "trl";
}
